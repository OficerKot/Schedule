//**Управление расписанием*/

import { WEEK_LENGTH, WORK_DAYS_CNT } from "../../constants.js";
import { getMonday } from "../../helpers/formatDate.js";
import { ScheduleData } from "../data/ScheduleData.js";
import { Classroom } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { testLesson1, testLesson2 } from "../model/Lesson.js";
import { StudyDay } from "../model/StudyDay.js";
import { Teacher } from "../model/Teacher.js";
import { createStudyDay } from "../view/StudyDayView.js";
import { FiltrationState } from "./FiltrationState.js";

export class ScheduleViewModel {
  private curWeekDate: Date = new Date();
  private dayElements: HTMLElement[] = [];
  private filters: FiltrationState = new FiltrationState();
  constructor(private data: ScheduleData) {}

  onTeacherFilterSelect(teacher: Teacher | null) {
    this.filters.teacher = teacher;
    console.log("selected" + teacher);
    this.update();
  }
  onGroupFilterSelect(group: Group | null) {
    this.filters.group = group;
    this.update();
  }
  onClassroomFilterSelect(classroom: Classroom | null) {
    this.filters.classroom = classroom;
    this.update();
  }

  //**Отобразить следующую неделю */
  showNextWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() + WEEK_LENGTH);
    this.update();
  }

  //**Отобразить предыдущую неделю */
  showPrevWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() - WEEK_LENGTH);
    this.update();
  }

  //**Отобразить текущую неделю */
  showCurWeek() {
    this.curWeekDate = new Date();
    this.update();
  }

  //**Отрисовка расписания*/
  private showWeek() {
    const studyDays = this.data.getStudyDays(this.filters);
    const curDate = getMonday(this.curWeekDate);

    for (let i = 0; i < WORK_DAYS_CNT; i++) {
      let day = studyDays.find(
        (d) => d.date.toDateString() == curDate.toDateString(),
      );

      if (!day) {
        day = new StudyDay([], curDate);
      }

      this.dayElements.push(createStudyDay(day, "scheduleContainer"));
      curDate.setDate(curDate.getDate() + 1);
    }
  }

  //**Очистка расписания перед отрисовкой новых столбцов */
  private clearWeek() {
    this.dayElements.forEach((element) => {
      element.remove();
    });
    this.dayElements = [];
  }

  //**Обновить данные и неделю */
  private update() {
    this.clearWeek();
    this.showWeek();
  }
}
