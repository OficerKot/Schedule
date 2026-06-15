import { Classroom } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { testLesson1, testLesson2 } from "../model/Lesson.js";
import { StudyDay } from "../model/StudyDay.js";
import { Teacher } from "../model/Teacher.js";
import { FiltrationState } from "../viewmodel/FiltrationState.js";

//**Получение данных из бд и их хранение */
export class ScheduleData {
  //Тут как раз все запросы к бд. Группы, учителя, аудитории запрашиваются 1 раз, при запуске, чтоб в доступных фильтрах их всех отображать
  //Занятия запрашиваются каждый раз при смене фильтра или недели

  private groups: Group[] = [];
  private teachers: Teacher[] = [];
  private classrooms: Classroom[] = [];

  //Тут без фильтров, получаем все данные
  getTeachersData(): Teacher[] {
    return [];
  }

  getGroupsData() {}

  getClassroomsData() {}
  //-------------------------------------

  getStudyDays(filters: FiltrationState): StudyDay[] {
    //Отправляет SQL запрос с учётом фильтров

    //Это убрать потом
    const testDay1 = new StudyDay(
      [testLesson1, testLesson2],
      new Date("06-15-2026"),
    );
    const testDay2 = new StudyDay(
      [testLesson1, testLesson1, testLesson2],
      new Date("06-17-2026"),
    );
    const testDay3 = new StudyDay([testLesson1], new Date("06-19-2026"));

    return [testDay1, testDay2, testDay3];
  }
}
