//**Управление расписанием*/

import { WEEK_LENGTH, WORK_DAYS_CNT } from "../constants.js";
import { getMonday } from "../helpers/formatDate.js";
import { testLesson1, testLesson2 } from "./model/Lesson.js";
import { StudyDay } from "./model/StudyDay.js";
import { createStudyDay } from "./view/StudyDayView.js";

export class ScheduleViewModel {
  constructor(
    private curWeekDate: Date = new Date(),
    private dayElements: HTMLElement[] = [],
  ) {}

  //**Отобразить следующую неделю */
  showNextWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() + WEEK_LENGTH);
    this.clearWeek();
    this.showWeek();
  }

  //**Отобразить предыдущую неделю */
  showPrevWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() - WEEK_LENGTH);
    this.clearWeek();
    this.showWeek();
  }

  //**Отобразить текущую неделю */
  showCurWeek() {
    this.curWeekDate = new Date();
    this.clearWeek();
    this.showWeek();
  }

  //**Отобразить расписание на неделю curWeekDate*/
  private showWeek() {
    const curDate = getMonday(this.curWeekDate);
    for (let i = 0; i < WORK_DAYS_CNT; i++) {
      //TODO: Нужно, чтобы день (где-то) заполнялся данными из бд
      const testDay = new StudyDay([testLesson1, testLesson2], curDate);

      this.dayElements.push(createStudyDay(testDay, "scheduleContainer"));
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
}
