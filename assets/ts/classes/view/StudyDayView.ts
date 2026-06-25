import { formatDateShort } from "../../helpers/formatDate.js";
import { Lesson, LessonType } from "../model/Lesson.js";
import { Teacher } from "../model/Teacher.js";
import { Classroom, ClassroomType } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { StudyDay } from "../model/StudyDay.js";
import { LessonCard } from "./LessonCard.js";

export function createStudyDay(
  studyDay: StudyDay,
  containerId: string,
): HTMLDivElement {
  const dayElem = document.createElement("div");
  dayElem.classList.add("dayColumn");

  // Проверяем, сегодня ли этот день
  const today = new Date();
  const todayStr = today.toISOString().split("T")[0];
  const dayStr =
    studyDay.date instanceof Date
      ? studyDay.date.toISOString().split("T")[0]
      : String(studyDay.date);
  if (dayStr === todayStr) {
    dayElem.classList.add("today");
  }

  // Проверяем выходной (воскресенье)
  const dateObj =
    studyDay.date instanceof Date ? studyDay.date : new Date(studyDay.date);
  if (dateObj.getDay() === 0) {
    dayElem.classList.add("weekend");
  }

  const dateContainer = createDateContainer(studyDay.date);
  dayElem.appendChild(dateContainer);

  const cardsContainer = document.createElement("div");
  cardsContainer.classList.add("cardsContainer");

  if (studyDay.lessons.length === 0) {
    const empty = document.createElement("div");
    empty.className = "empty-day";
    empty.textContent = "Нет занятий";
    cardsContainer.appendChild(empty);
  } else {
    studyDay.lessons.forEach((lesson) => {
      cardsContainer.appendChild(addLessonCard(lesson));
    });
  }

  dayElem.appendChild(cardsContainer);

  const container = document.getElementById(containerId);
  container?.appendChild(dayElem);

  return dayElem;
}

function createDateContainer(date: Date): HTMLDivElement {
  const dateElem = document.createElement("div");
  dateElem.classList.add("dateContainer");

  const dateObj = date instanceof Date ? date : new Date(date);
  const dayNames = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
  const monthNames = [
    "янв",
    "фев",
    "мар",
    "апр",
    "май",
    "июн",
    "июл",
    "авг",
    "сен",
    "окт",
    "ноя",
    "дек",
  ];

  dateElem.innerHTML = `
    <span class="day-name">${dayNames[dateObj.getDay()]}</span>
    <span class="day-number">${dateObj.getDate()}</span>
    <span class="day-month">${monthNames[dateObj.getMonth()]}</span>
  `;

  return dateElem;
}

function addLessonCard(lesson: Lesson): HTMLDivElement {
  const cardElem = new LessonCard(lesson);
  return cardElem.getDivElement();
}
