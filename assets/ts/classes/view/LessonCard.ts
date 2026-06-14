import { Lesson } from "../model/Lesson.js";

export class LessonCard {
  //в будущем карточка будет кликабельна, будет раскрываться подробная информация
  private isClicked: Boolean = false;
  private divElem!: HTMLDivElement;

  constructor(private lesson: Lesson) {
    this.createCard();
  }

  getDivElement(): HTMLDivElement {
    return this.divElem;
  }

  //**Создание div элемента с карточкой занятия */
  private createCard() {
    //TODO: Добавить дизайн для карточки!

    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `
    <div>${this.lesson.lessonName}</div>
    <div>${this.lesson.lessonType}</div>
    <div>${this.lesson.teacher.last_name} ${this.lesson.teacher.first_name} ${this.lesson.teacher.middle_name}</div>
    <div>${this.lesson.group.name}</div>
    <div>${this.lesson.classroom.building + this.lesson.classroom.classroomNumber}</div>
  `;

    this.divElem = card;
  }
}
