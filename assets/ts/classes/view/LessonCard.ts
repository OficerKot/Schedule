import { Lesson } from "../model/Lesson.js";

export class LessonCard {
  private divElem!: HTMLDivElement;
  private dialogElem!: HTMLDialogElement;

  constructor(private lesson: Lesson) {
    this.createCard();
    this.createDialog();

    this.divElem.addEventListener("click", () => this.handleClick());
  }

  getDivElement(): HTMLDivElement {
    return this.divElem;
  }

  private createCard() {
    this.divElem = document.createElement("div");

    // ===== ВАЖНО: Добавляем классы для CSS =====
    const typeMap: Record<string, string> = {
      Лекция: "lecture",
      Практика: "practical",
      "Лабораторные работы": "lab",
      Зачёт: "credit",
      Экзамен: "exam",
    };

    const typeClass = typeMap[this.lesson.lessonType] || "lecture";
    this.divElem.classList.add("card", typeClass);

    // Форматируем имя преподавателя
    const teacherName = `${this.lesson.teacher.last_name} ${this.lesson.teacher.first_name.charAt(0)}.${this.lesson.teacher.middle_name.charAt(0)}.`;

    this.divElem.innerHTML = `
      <div class="lesson-time">${this.lesson.lessonNumber} пара</div>
      <div class="lesson-name">${this.lesson.lessonName}</div>
      <div class="lesson-meta">
        <span class="lesson-type">${this.lesson.lessonType}</span>
        <span class="teacher">${teacherName}</span>
        <span class="classroom">${this.lesson.classroom.building}-${this.lesson.classroom.classroomNumber}</span>
        <span class="group">${this.lesson.group.name}</span>
      </div>
    `;
  }

  private createDialog() {
    this.dialogElem = document.createElement("dialog");
    this.dialogElem.style.cssText = `
  background: #161b22;
  color: #dde4f0;
  border: 1px solid rgba(255,255,255,0.14);
  border-radius: 14px;
  padding: 24px;
  max-width: 400px;
  width: 90%;
  font-family: 'Inter', sans-serif;
  box-shadow: 0 12px 40px rgba(0,0,0,0.6);
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  margin: 0;
`;

    const teacherName = `${this.lesson.teacher.last_name} ${this.lesson.teacher.first_name} ${this.lesson.teacher.middle_name}`;

    this.dialogElem.innerHTML = `
        <h3 style="margin: 0 0 16px 0; color: #dde4f0;">${this.lesson.lessonName}</h3>
        <div style="margin: 8px 0; color: #8590a8;"><strong style="color: #7aa3ff;">Тип:</strong> ${this.lesson.lessonType}</div>
        <div style="margin: 8px 0; color: #8590a8;"><strong style="color: #7aa3ff;">Преподаватель:</strong> ${teacherName}</div>
        <div style="margin: 8px 0; color: #8590a8;"><strong style="color: #7aa3ff;">Группа:</strong> ${this.lesson.group.name} (${this.lesson.group.studentCount} чел.)</div>
        <div style="margin: 8px 0; color: #8590a8;"><strong style="color: #7aa3ff;">Аудитория:</strong> ${this.lesson.classroom.building}-${this.lesson.classroom.classroomNumber} (${this.lesson.classroom.seats} мест)</div>
        <button style="margin-top: 16px; padding: 8px 20px; background: #4f7fff; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-family: 'Inter', sans-serif;">Закрыть</button>
    `;

    const closeBtn = this.dialogElem.querySelector("button");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => this.dialogElem.close());
    }

    document.body.appendChild(this.dialogElem);
  }

  private handleClick() {
    if (!this.dialogElem.open) {
      this.dialogElem.showModal();
    } else {
      this.dialogElem.close();
    }
  }
}
