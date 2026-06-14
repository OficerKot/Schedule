import { formatDateShort } from "../../helpers/formatDate.js";
import { Lesson, LessonType } from "../model/Lesson.js";
import { Teacher } from "../model/Teacher.js";
import { Classroom, ClassroomType } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { StudyDay } from "../model/StudyDay.js";
import { LessonCard } from "./LessonCard.js";

/** Отрисовка колонки учебного дня */
export function createStudyDay(
  studyDay: StudyDay,
  containerId: string,
): HTMLDivElement {
  const dayElem = document.createElement("div");
  dayElem.classList.add("dayColumn");

  const dateContainer = createDateContainer(studyDay.date);
  dayElem.appendChild(dateContainer);

  const cardsContainer = document.createElement("div");
  cardsContainer.classList.add("cardsContainer");

  //Это для проверки карточек, потом убрать!!
  studyDay.lessons.forEach((lesson) => {
    cardsContainer.appendChild(addLessonCard(lesson));
  });

  dayElem.appendChild(cardsContainer);

  const container = document.getElementById(containerId);
  container?.appendChild(dayElem);

  return dayElem;
}

/** Возвращает контейнер с преобразованной (день_недели, день.месяц) датой */
function createDateContainer(date: Date): HTMLDivElement {
  const dateElem = document.createElement("div");
  dateElem.classList.add("dateContainer");
  dateElem.innerHTML = formatDateShort(date);
  return dateElem;
}

/** Добавление карточки в учебный день */
function addLessonCard(lesson: Lesson): HTMLDivElement {
  const cardElem = new LessonCard(lesson);
  return cardElem.getDivElement();
}
