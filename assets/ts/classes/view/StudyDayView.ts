import { formatDateShort } from "../../helpers/formatDate.js";
import { StudyDay } from "../model/StudyDay.js";

/** Отрисовка колонки учебного дня */
export function createStudyDay(studyDay: StudyDay, containerId: string): void {
  const dayElem = document.createElement("div");
  dayElem.classList.add("dayColumn");

  const dateContainer = createDateContainer(studyDay.date);
  dayElem.appendChild(dateContainer);

  const cardsContainer = document.createElement("div");
  cardsContainer.classList.add("cardsContainer");
  dayElem.appendChild(cardsContainer);

  const container = document.getElementById(containerId);
  container?.appendChild(dayElem);
}

/** Преобразует дату в день месяца и день недели, возвращает div с этими данными */
function createDateContainer(date: Date): HTMLDivElement {
  const dateElem = document.createElement("div");
  dateElem.classList.add("dateContainer");
  dateElem.innerHTML = formatDateShort(date);
  return dateElem;
}

/** Добавление карточки в учебный день */
function addLessonCard(): void {
  // TODO: реализовать добавление карточки занятия
}
