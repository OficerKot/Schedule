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
  private data: ScheduleData = new ScheduleData();

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
    const studyDays = this.data.getStudyDays(this.filters);
    const curDate = getMonday(this.curWeekDate);

    for (let i = 0; i < WORK_DAYS_CNT; i++) {
      let day = studyDays.find((d) => d.date.getDate() == curDate.getDate());
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
