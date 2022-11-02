import cr from "@assets/js/functions/cr";
import Modal from "@assets/js/interfaces/Modal";

export default class Terminal {
  keys: string[] = [];
  previewKeys: string[] = [];
  specialKeys: string[] = [];
  multipleKeys: string[] = [];
  historyLog: string[] = [];
  commandLog: string[] = [];
  commandLogCount = 1;
  element: HTMLDivElement;
  modal: Modal;
  body: HTMLDivElement;
  input: HTMLDivElement;
  inputMessage: string = "";
  inputAction: any = null;
  inputFilter: any = null;
  inputFilters: any;
  inputStrings: string[] = [];
  text: HTMLDivElement;
  username: HTMLDivElement;
  cursorElement: HTMLSpanElement;

  /**
   * Constructor.
   *
   * @param element Terminal element.
   */
  constructor(element: HTMLDivElement) {
    this.element = element;
    this.body = element.querySelector(".terminal__body");
    this.input = element.querySelector(".terminal__input");
    this.text = element.querySelector(".terminal__text");
    this.username = element.querySelector(".terminal__username");
    this.createModalElement();
    this.setInputFilters();
    this.setSpecialKeys();
    this.createCursor();
    this.modalListener();
    this.setWelcomeMessages();
  }

  /**
   * Set welcome messages.
   *
   * @private
   */
  private setWelcomeMessages() {
    this.echo("My name is Marius and I'm an up-and-coming-developer.");
    this.echo("Feel free to explore my website and learn more about me.");
    this.echo("Type 'help' to see a list of commands.");
  }

  /**
   * Set input filters.
   *
   * @private
   */
  private setInputFilters() {
    this.inputFilters = {
      Asterisk: 0,
    };
  }

  /**
   * Run the terminal.
   *
   */
  public run() {
    this.setTextDivHeight();
    this.showUserInput();
    this.getUserInput();
  }

  /**
   * Create the "fake" cursor.
   *
   * @private
   */
  private createCursor() {
    this.cursorElement = cr("span", { class: "terminal__text_cursor" });
  }

  /**
   * Display the user input.
   *
   * @private
   */
  private showUserInput() {
    this.text.innerHTML = "";
    this.text.appendChild(this.username);

    const messageDiv = cr("div", { class: "terminal__input-message" });
    messageDiv.innerHTML = this.inputMessage !== "" ? this.inputMessage + "&nbsp;" : "";
    this.text.appendChild(messageDiv);

    this.keys = this.keys.map((key) => {
      if (key === " ") {
        return "&nbsp;";
      }

      return key;
    });

    this.previewKeys = this.keys;

    if (this.inputFilter === this.inputFilters.Asterisk) {
      this.previewKeys = this.previewKeys.map((key) => {
        return "*";
      });
    }

    this.previewKeys.forEach((key) => {
      const div = document.createElement("div");
      div.setAttribute("class", "terminal__key");
      div.innerHTML = key;
      this.text.appendChild(div);
    });

    this.text.appendChild(this.cursorElement);
  }

  /**
   * Scroll to the bottom of the history log.
   *
   * @private
   */
  private scrollToBottom() {
    this.body.scrollTop = this.body.scrollHeight;
  }

  private getUserInput() {
    document.addEventListener("keydown", (e) => {
      if (this.specialKeys.includes(e.key)) {
        return;
      }

      // If multiple keys are pressed, use these conditions.
      if (this.multipleKeys.length > 0) {
        switch (e.key) {
          case "c":
            this.keys = [];
            this.previewKeys = [];
            this.showUserInput();
            break;
          case "k":
            this.clearHistory();
            break;
        }

        this.multipleKeys = [];
        return;
      }

      switch (e.key) {
        case "ArrowLeft":
        case "ArrowRight":
          break;
        case "ArrowUp":
          this.showCommand();
          if (this.commandLogCount < this.commandLog.length) {
            this.commandLogCount++;
          }
          break;
        case "ArrowDown":
          if (this.commandLogCount > 1) {
            this.commandLogCount--;
          }
          this.showCommand();
          break;
        case " ":
          this.keys.push("&nbsp;");
          this.showUserInput();
          break;
        case "Backspace":
          this.keys.pop();
          this.showUserInput();
          break;
        case "Control":
          this.multipleKeys.push("Control");
          break;
        case "Enter":
          this.handleUserInput();
          this.keys = [];
          this.showUserInput();
          break;
        default:
          this.keys.push(e.key);
          this.showUserInput();
      }
    });

    this.body.addEventListener("DOMSubtreeModified", () => {
      this.scrollToBottom();
    });
  }

  private setSpecialKeys() {
    this.specialKeys = [
      "Shift",
      "Alt",
      "Meta",
      "CapsLock",
      "Dead",
      "F1",
      "F2",
      "F3",
      "F4",
      "F5",
      "F6",
      "F7",
      "F8",
      "F9",
      "F10",
      "F11",
      "F12",
    ];
  }

  private setTextDivHeight() {
    const height = this.input.clientHeight;

    this.text.style.minHeight = `${height}px`;
  }

  private handleUserInput() {
    const command = this.keys.join("");

    this.commandLogCount = 1;

    this.executeCommand(command);
  }

  private executeCommand(command: string) {
    const commands = this.commands();
    const formattedCommand = command.replace(/&nbsp;/g, " ").trim();

    if (this.inputAction !== null) {
      this.inputAction(formattedCommand);
      return;
    }

    this.historyLog.push(formattedCommand);
    this.updateHistory();

    this.commandLog.push(command);

    // Command and flags.
    const flags = formattedCommand.split(" ");
    const commandName = flags.shift();
    const commandFlag = flags.shift();
    const commandArg = flags.shift();
    const commandArgs = flags.join(" ");

    const commandFunction = commands[formattedCommand];

    if (commandFunction) {
      commandFunction();
    } else {
      this.echo("bsh: command not found: " + formattedCommand);
    }
  }

  private commands(): object {
    return {
      clear: (flag: string) => {
        this.clearHistory();
      },

      help: () => {
        this.help();
      },

      login: () => {
        this.login()["step1"]();
      },

      logout: () => {
        this.logout()["step1"]();
      },

      refresh: () => {
        this.echo("Refreshing...");
        this.refreshPage();
      },

      about: () => {
        this.about();
      },
    };
  }

  private help() {
    const commands = Object.keys(this.commands()).map((key) => {
      return key;
    });

    this.echo("Some commands you can use:");
    commands.forEach((command) => {
      this.echo("- " + command);
    });
  }

  /**
   * Echo a message to the terminal.
   *
   * @param message Message to display.
   * @private
   */
  private echo(message: any) {
    this.historyLog.push(String(message));
    this.updateHistory();
  }

  /**
   * Clear the history.
   *
   * @private
   */
  private clearHistory() {
    this.historyLog = [];
    this.updateHistory();
  }

  private updateHistory() {
    const history = document.querySelector(".terminal__log_history");
    history.innerHTML = "";
    this.historyLog.forEach((log) => {
      const div = document.createElement("div");
      div.setAttribute("class", "terminal__log_line");
      div.innerHTML = log;
      history.appendChild(div);
    });
  }

  private showCommand() {
    if (this.commandLog.length > 0) {
      let command = this.commandLog[this.commandLog.length - this.commandLogCount];
      command = command.replace(/&nbsp;/g, " ");
      this.keys = command.split("");
      this.showUserInput();
    }
  }

  /**
   * Login to a site user account.
   *
   * @private
   */
  private login(): object {
    return {
      step1: () => {
        this.inputMessage = "Username: ";
        this.inputAction = async (input) => {
          this.login()["step2"](input);
        };
      },

      step2: (input: string) => {
        this.inputStrings.push(input);
        this.inputMessage = "Password: ";
        this.inputFilter = this.inputFilters.Asterisk;
        this.inputAction = async (input: string) => {
          await this.login()["step3"](input);
        };
      },

      step3: async (input: string) => {
        const username = this.inputStrings[0];

        this.echo("Logging in...");
        this.clearInputData();

        const response = await fetch("/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify({
            username: username,
            password: input,
          }),
        });

        const data = await response.json();

        if (data.user) {
          this.echo("Login successful!");
          this.echo("Welcome " + data.user + "!");
          this.echo("Would you like to refresh the page? (y/n)");
          this.inputMessage = "(y/n)";
          this.inputAction = (input) => {
            this.login()["success"](input);
          };
          this.showUserInput();
        }

        if (data.error) {
          this.echo("Login failed: " + data.error);
        }
      },

      success: (input: string) => {
        if (input === "y") {
          this.echo("Refreshing page...");
          window.location.reload();
        }

        this.clearInputData();
      },
    };
  }

  private logout(): object {
    return {
      step1: () => {
        this.echo("Are you sure you want to logout? (y/n)");
        this.inputMessage = "(y/n)";
        this.inputAction = (input) => {
          this.logout()["step2"](input);
        };
      },

      step2: (input: string) => {
        if (input === "y") {
          this.clearInputData();
          this.echo("Logging out...");
          this.redirectTo("/logout");
        } else {
          this.clearInputData();
          this.echo("Logout cancelled.");
        }
      },
    };
  }

  /**
   * Refresh the page.
   *
   * @param ms Milliseconds to wait before refreshing. Default is 0.
   * @private
   */
  private refreshPage(ms: number = 0) {
    setTimeout(() => {
      window.location.reload();
    }, ms);
  }

  private redirectTo(url: string, ms: number = 0) {
    setTimeout(() => {
      window.location.href = url;
    }, ms);
  }

  /**
   * Clear the input data after a multistep input.
   *
   * @private
   */
  private clearInputData() {
    this.inputMessage = "";
    this.inputAction = null;
    this.inputFilter = null;
    this.inputStrings = [];

    this.showUserInput();
  }

  /**
   * Create the modal element.
   *
   * @private
   */
  private createModalElement() {
    this.modal = {
      element: cr("div", { class: "terminal-modal" }),
      content: {
        element: cr("div", { class: "terminal-modal__content" }),
        header: cr("div", { class: "terminal-modal__header" }),
        body: cr("div", { class: "terminal-modal__body" }),
        footer: cr("div", { class: "terminal-modal__footer" }),
      },
    };

    this.modal.content.element.appendChild(this.modal.content.header);
    this.modal.content.element.appendChild(this.modal.content.body);
    this.modal.content.element.appendChild(this.modal.content.footer);

    this.modal.element.appendChild(this.modal.content.element);

    const siteBody = document.querySelector("body");
    if (siteBody) {
      siteBody.prepend(this.modal.element);
    }

    this.setModalContent();
  }

  /**
   * Modal related events.
   *
   * @private
   */
  private modalListener() {
    const openModalBtn = this.element.querySelector(".terminal__open-modal span");
    const closeModalBtn = this.modal.content.element.querySelector(".terminal-modal__close");

    openModalBtn.addEventListener("click", () => {
      this.modal.element.classList.add("terminal-modal_open");
    });

    closeModalBtn.addEventListener("click", () => {
      this.modal.element.classList.remove("terminal-modal_open");
    });
  }

  private setModalContent() {
    const modalClose = cr("div", { class: "terminal-modal__close" });
    modalClose.innerHTML = '<span class="material-icons-outlined">close</span>';
    this.modal.content.element.appendChild(modalClose);

    // this.modal.content.header.innerHTML = "<h1>Modal</h1>";
  }

  private getAge(birthday: string) {
    const ageDifMs = Date.now() - new Date(birthday).getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
  }

  private about() {
    this.echo("Name: Marius Toresen Bjercke");
    this.echo("Age: " + this.calculateAge("1994-01-16"));
    this.echo("Occupation: Developer in practice");
  }

  private calculateAge(birthday: string) {
    const ageDifMs = Date.now() - new Date(birthday).getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
  }
}
