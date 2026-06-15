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

  //**Создание div элемента с карточкой занятия */
  private createCard() {
    //TODO: Добавить дизайн для карточки!

    this.divElem = document.createElement("div");
    this.divElem.classList.add("card");
    this.divElem.innerHTML = `
    <div>${this.lesson.lessonName}</div>
    <div>${this.lesson.lessonType}</div>
    <div>${this.lesson.group.name}</div>
    <div>${this.lesson.classroom.building + this.lesson.classroom.classroomNumber}</div>
  `;
  }

  //**Создаёт диалоговое окно с полной информацией о занятии */
  private createDialog() {
    this.dialogElem = document.createElement("dialog");
    this.dialogElem.innerHTML = `
	<div>Дисциплина: ${this.lesson.lessonName}</div>
    <div>Тип занятия: ${this.lesson.lessonType}</div>
    <div>Преподаватель: ${this.lesson.teacher.last_name} ${this.lesson.teacher.first_name} ${this.lesson.teacher.middle_name}</div>
    <div>Группа: ${this.lesson.group.name}, ${this.lesson.group.studentCount}чел.</div>
    <div>Аудитория: ${this.lesson.classroom.building + this.lesson.classroom.classroomNumber}</div>
	`;

    const closeBtn = document.createElement("button");
    closeBtn.textContent = "Закрыть";
    closeBtn.addEventListener("click", () => this.dialogElem.close());

    this.dialogElem.appendChild(closeBtn);
    document.body.appendChild(this.dialogElem);
  }

  //**Управляет открытием/закрытием диалогового окна */
  private handleClick() {
    if (!this.dialogElem.open) {
      this.dialogElem.showModal();
    } else {
      this.dialogElem.close();
    }
  }
}
