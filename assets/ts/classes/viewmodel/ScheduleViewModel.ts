//**Управление расписанием*/

import { WEEK_LENGTH, WORK_DAYS_CNT } from "../../constants.js";
import { getMonday } from "../../helpers/formatDate.js";
import { ScheduleData } from "../data/ScheduleData.js";
import { Classroom } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { StudyDay } from "../model/StudyDay.js";
import { Teacher } from "../model/Teacher.js";
import { createStudyDay } from "../view/StudyDayView.js";
import { FiltrationState } from "./FiltrationState.js";

export class ScheduleViewModel {
  private curWeekDate: Date = new Date(2026, 8, 1); // 1 сентября 2026 (понедельник)
  private dayElements: HTMLElement[] = [];
  private filters: FiltrationState = new FiltrationState();
  public data: ScheduleData = new ScheduleData();

  onTeacherFilterSelect(teacher: Teacher | null) {
    this.filters.teacher = teacher;
  }
  onGroupFilterSelect(group: Group | null) {
    this.filters.group = group;
  }
  onClassroomFilterSelect(classroom: Classroom | null) {
    this.filters.classroom = classroom;
  }

  //**Отобразить следующую неделю */
  async showNextWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() + WEEK_LENGTH);
    this.clearWeek();
    await this.showWeek();
  }

  //**Отобразить предыдущую неделю */
  async showPrevWeek() {
    this.curWeekDate.setDate(this.curWeekDate.getDate() - WEEK_LENGTH);
    this.clearWeek();
    await this.showWeek();
  }

  //**Отобразить текущую неделю (реальная дата) */
  async showCurWeek() {
    this.curWeekDate = new Date();
    this.clearWeek();
    await this.showWeek();
  }

  //**Отобразить неделю по сохранённой дате */
  async showCurrentSavedWeek() {
    this.clearWeek();
    await this.showWeek();
  }

  //**Отобразить расписание на неделю curWeekDate*/
  private async showWeek() {
    const curDate = getMonday(this.curWeekDate);
    const studyDays = await this.data.getStudyDays(this.filters, curDate);

    for (let i = 0; i < WORK_DAYS_CNT; i++) {
      const dateStr = curDate.toISOString().split('T')[0];
      let day = studyDays.find((d) => {
        const dStr = d.date instanceof Date ? d.date.toISOString().split('T')[0] : String(d.date);
        return dStr === dateStr;
      });
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
}
