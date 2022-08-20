import { Controller } from "@hotwired/stimulus";
import tinymce from "@assets/js/functions/tinymce";

export default class extends Controller {
  static targets = ["title", "content", "newPost", "postsContainer", "form"];
  private editor: any;

  titleTarget: HTMLInputElement;
  contentTarget: any;
  newPostTarget: HTMLDivElement;
  postsContainerTarget: HTMLDivElement;
  formTarget: HTMLFormElement;

  connect() {
    this.editor = tinymce.init({
      target: this.contentTarget,
      content_css: "/build/wysiwyg.css",
      plugins: "advlist code emoticons link lists table",
      toolbar: "bold italic | bullist numlist | link emoticons",
      toolbar_mode: "floating",
      width: "100%",
    });
  }

  newPost() {
    this.newPostTarget.classList.add("blog__new-post_show");
  }

  async submit(e) {
    e.preventDefault();

    // Update the form with the new WYSIWYG content
    this.contentTarget.value = tinymce.activeEditor.getContent();

    const formData = new FormData(this.formTarget);

    // Use X-Requested-With: XMLHttpRequest to prevent CSRF
    const response = await fetch("/blog/post/new", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const data = await response.json();

    if (data.serverError) {
      throw new Error(data.serverError);
    }

    // Remove all existing errors
    this.formTarget.querySelectorAll(".blog__error").forEach((error) => {
      error.remove();
    });

    if (!data.success) {
      data.result.errors.forEach((error) => {
        const inputDiv = this.formTarget.querySelector(`[name="blog_post[${error.input}]"]`).parentElement
          .parentElement;

        const errorElement = document.createElement("div");
        errorElement.classList.add("blog__error");
        errorElement.innerHTML = error.message;

        inputDiv.appendChild(errorElement);
      });

      return;
    }

    if (data.success) {
      this.addPlaceholderPost(data.result);

      // Reset the form
      this.titleTarget.value = "";
      tinymce.activeEditor.setContent("");

      this.close();
    }
  }

  close() {
    this.newPostTarget.classList.remove("blog__new-post_show");
  }

  addPlaceholderPost(result) {
    const dummy: Element = this.postsContainerTarget.querySelectorAll(".blog__post")[0];
    const post = dummy.cloneNode(true) as HTMLDivElement;
    const noPostsYetMessage = this.postsContainerTarget.querySelector(".blog__no-posts-yet");
    post.classList.remove("blog__post_hidden");
    if (post.hasAttribute("data-blog-target")) {
      post.removeAttribute("data-blog-target");
    }
    post.querySelector(".blog__post-title").innerHTML = result.title;
    post.querySelector(".blog__post-content").innerHTML = result.content;
    post.querySelector(".blog__post-author").innerHTML = result.author;

    // Remove the no posts yet message if it exists
    if (noPostsYetMessage) {
      noPostsYetMessage.remove();
    }

    // Add the new post to top of the list
    this.postsContainerTarget.prepend(post);
  }
}
