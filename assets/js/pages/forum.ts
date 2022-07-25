import { DOMReady } from "@assets/js/shared/domready";
import { ajaxPostJson } from "@assets/js/functions/ajax";

DOMReady(() => {
  const element = document.querySelector(".forum");

  if (element) {
    const submitBtn = element.querySelector("#submit");
    const closeBtn = element.querySelector("#close");
    const titleInput = element.querySelector("#title") as HTMLInputElement;
    const contentInput = element.querySelector("#content") as HTMLInputElement;
    const existingPosts = element.querySelectorAll(".forum__latest-posts-item");
    const newPost = element.querySelector(".forum__new-post");

    // Return if no new post block is found. User is probably not logged in.
    if (!newPost) {
      return;
    }

    // Initialize TinyMCE editor.
    // const editor = initWYSIWYG(contentInput);

    const newPostForm = element.querySelector(".forum__new-post-form");
    const toggleBtn = element.querySelector(".forum__new-post-btn");

    const buttons = [toggleBtn, closeBtn];
    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        newPostForm.classList.toggle("forum__new-post-form_active");
        toggleBtn.classList.toggle("hidden");
      });
    });

    // Add listener for "enter" key.
    // element.addEventListener("keydown", (e) => {
    //     if (e.keyCode === 13) {
    //         submitBtn.click();
    //     }
    // });

    submitBtn.addEventListener("click", (e) => {
      let formData = new FormData();
      formData.append("action", "forum_post");
      formData.append("title", titleInput.value);
      formData.append("content", contentInput.value);

      function callback(response) {
        // Add new post to the list.
        const postsList = element.querySelector(".forum__latest-posts-list");
        const existingPost = existingPosts[0];
        const newPost = existingPost.cloneNode(true) as HTMLElement;
        const newPostTitle = newPost.querySelector(".forum__latest-posts-item-title");
        const newPostContent = newPost.querySelector(".forum__latest-posts-item-content");
        const newPostAuthor = newPost.querySelector(".forum__latest-posts-item-info-author_field");
        const newPostTimeAgo = newPost.querySelector(".forum__latest-posts-item-info-date_timeago");

        // Remove hidden class from first post.
        if (newPost.classList.contains("hidden")) {
          newPost.classList.remove("hidden");
        }

        // Remove no posts-element if exists.
        const noPosts = element.querySelector(".forum__no-posts");
        if (noPosts) {
          noPosts.remove();
        }

        newPostTitle.innerHTML = response["title"];
        newPostContent.innerHTML = response["content"];
        newPostAuthor.innerHTML = response["author"];
        newPostTimeAgo.innerHTML = response["time_ago"];

        postsList.prepend(newPost);

        // Hide the form after submit.
        if (newPostForm.classList.contains("forum__new-post-form_active")) {
          newPostForm.classList.toggle("forum__new-post-form_active");
          toggleBtn.classList.toggle("hidden");
        }

        // TODO: Clear editor.
        contentInput.value = "";
      }

      let inputArr = [titleInput, contentInput];

      fetch("/forum/new-post", {
        method: "POST",
        body: formData,
      }).then((r) => {
        r.json().then((response) => {
          console.log(response);
        });
      });
    });
  }
});
