import {GameEntity} from "./GameEntity";

export class Player extends GameEntity {
  private readonly keys: Array<string>;
  private readonly walkSpeed: number;
  private readonly runSpeed: number;
  public data: any;
  public currentAnimFrame: number;
  private readonly frames: any;
  private resetFrames: boolean;

  constructor(data: any) {
    const position = {
      x: 0,
      y: 0,
    };
    const size = {
      width: data.frames[0].sourceSize.w,
      height: data.frames[0].sourceSize.h,
    };
    super(position, size);
    this.data = data;
    this.moveSpeed = 3;
    this.walkSpeed = this.moveSpeed;
    this.runSpeed = 10;
    this.friction = 0.85;
    this.gravity = 0.85;
    this.grounded = false;
    this.keys = [];
    this.currentAnimFrame = 0;
    this.frames = {
      elapsed: 0,
      maxWalk: 30,
      maxRun: 15
    };
    this.frames.max = this.frames.maxWalk;
    this.init();
  }

  public update(): void {
    this.playerMovement();
    this.worldBoundsCheck();
    this.updateFrames();
  }

  private updateFrames(): void {
    if (this.frames.elapsed < this.frames.max) {
      this.frames.elapsed++;
    }

    this.updateAnimation();

    if (this.frames.elapsed === this.frames.max) {
      this.frames.elapsed = 0;
    }
  }

  public collideY(): void {
    this.velY = 0;
  }

  public collideX() {
    this.velX = 0;
  }

  private initGravity(): void {
    if (!this.grounded) {
      this.velY += this.gravity;
    }
  }

  private playerMovement() {
    // Running
    if (this.keys["Shift"]) {
      this.moveSpeed = this.runSpeed;
      if (this.frames.elapsed === 0) {
        this.frames.max = this.frames.maxRun;
      }
    } else {
      this.moveSpeed = this.walkSpeed;
      if (this.frames.elapsed === 0) {
        this.frames.max = this.frames.maxWalk;
      }
    }

    if (this.keys["ArrowLeft"]) {
      if (this.velX > -this.moveSpeed) {
        this.velX--;
      }
      this.flipX = true;
    }

    if (this.keys["ArrowRight"]) {
      if (this.velX < this.moveSpeed) {
        this.velX++;
      }
      this.flipX = false;
    }

    if (this.keys["ArrowUp"]) {
      if (this.velY > -this.moveSpeed) {
        this.velY--;
      }
    }

    if (this.keys["ArrowDown"]) {
      if (this.grounded) return;

      if (this.velY < this.moveSpeed) {
        this.velY++;
      }
    }

    this.position.x += this.velX;
    this.position.y += this.velY;

    // Friction
    this.velX *= this.friction;
    this.velY *= this.friction;
  }

  public init() {
    this.eventListeners();
  }

  public worldBoundsCheck() {
    if (this.position.x > this.ctx.canvas.width + this.size.width) {
      this.position.x = -this.size.width * 1.5;
    } else if (this.position.x < -this.size.width * 1.5) {
      this.position.x = this.ctx.canvas.width + this.size.width;
    }
  }

  public eventListeners() {
    document.addEventListener("keydown", (e) => {
      this.keys[e.key] = true;
    });

    document.addEventListener("keyup", (e) => {
      this.keys[e.key] = false;
    });
  }

  public updateAnimation() {
    if (Math.abs(this.velX) > 1 || Math.abs(this.velY) > 1) {
      if (this.frames.elapsed === this.frames.max) {
        this.currentAnimFrame++;
        if (this.currentAnimFrame > this.data.frames.length - 1) {
          this.currentAnimFrame = 1;
        }
      }
    } else {
      this.currentAnimFrame = 0;
    }
  }

  public draw(ctx: CanvasRenderingContext2D) {
    const width = this.data.frames[this.currentAnimFrame].frame.w;
    const height = this.data.frames[this.currentAnimFrame].frame.h;
    const scale = 1;

    if (this.flipX) {
      ctx.save();
      ctx.scale(-scale, scale);
      ctx.drawImage(this.sprite.image, this.data.frames[this.currentAnimFrame].frame.x, this.data.frames[this.currentAnimFrame].frame.y, width, height, -this.position.x - width, this.position.y, width, height);
      ctx.restore();
    } else {
      ctx.save();
      ctx.scale(scale, scale);
      ctx.drawImage(this.sprite.image, this.data.frames[this.currentAnimFrame].frame.x, this.data.frames[this.currentAnimFrame].frame.y, width, height, this.position.x, this.position.y, width, height);
      ctx.restore();
    }
  }

}