import { Classroom, parseClassroomType } from "../model/Classroom.js";
import { Group } from "../model/Group.js";
import { Lesson, LessonType } from "../model/Lesson.js";
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

  //Запрос на получение всего списка учителей
  async getTeachersData(): Promise<Teacher[]> {
    const res = await fetch("api.php?action=teachers");
    const data = await res.json();
    this.teachers = data.map((t: any) => new Teacher(
      t.teacher_id,
      t.first_name,
      t.middle_name || "",
      t.last_name,
      t.school || "",
      t.position || "",
      t.chair || "",
      t.department || "",
      t.degree,
      t.title,
    ));
    return this.teachers;
  }

  async getGroupsData(): Promise<Group[]> {
    const res = await fetch("api.php?action=groups");
    const data = await res.json();
    this.groups = data.map((g: any) => new Group(g.name, g.students_count, g.group_id));
    return this.groups;
  }

  async getClassroomsData(): Promise<Classroom[]> {
    const res = await fetch("api.php?action=classrooms");
    const data = await res.json();
    this.classrooms = data.map((r: any) => new Classroom(
      r.building || "",
      r.room_number,
      r.seats,
      parseClassroomType(r.room_type),
    ));
    return this.classrooms;
  }
  // ScheduleData.ts

  async getGroupById(groupId: number): Promise<Group | null> {
      // Если группы уже загружены — ищем в кэше
      if (this.groups.length > 0) {
          return this.groups.find(g => {
              const id = (g as any).groupId || (g as any).group_id;
              return id === groupId;
          }) || null;
      }
      
      // Если нет — делаем запрос к БД
      const res = await fetch(`api.php?action=group&id=${groupId}`);
      const data = await res.json();
      if (!data) return null;
      
      return new Group(data.name, data.students_count, data.group_id);
  }
  async getStudyDays(filters: FiltrationState, monday: Date): Promise<StudyDay[]> {
    const params = new URLSearchParams({
      action: "schedule",
      monday: monday.toISOString().split("T")[0],
    });

    if (filters.teacher) params.set("teacher", String(filters.teacher.id));
    if (filters.group) {
      const g = filters.group;
      const gid = (g as any).groupId || (g as any).group_id;
      if (gid) params.set("group", String(gid));
    }
    if (filters.classroom) {
      const c = filters.classroom;
      params.set("classroom", (c.building || "") + (c.classroomNumber || ""));
    }

    const res = await fetch("api.php?" + params.toString());
    const data = await res.json();

    return data.map((day: any) => {
      const lessons = day.lessons.map((l: any) => {
        // Маппинг названий типов из БД в TS enum
        const typeMap: Record<string, LessonType> = {
          "Лекция": LessonType.LECTION,
          "Практическое занятие": LessonType.PRACTICE,
          "Лабораторная работа": LessonType.LAB,
          "Зачёт": LessonType.CREDIT,
          "Экзамен": LessonType.EXAM,
          "Консультация": LessonType.CREDIT,
        };
        const lessonType = typeMap[l.lesson_type] || LessonType.PRACTICE;

        return new Lesson(
          l.lesson_name,
          l.lesson_number,
          lessonType,
          new Teacher(
            l.teacher.id,
            l.teacher.first_name,
            l.teacher.middle_name || "",
            l.teacher.last_name,
            "", "", "", "", undefined, undefined,
          ),
          new Classroom(
            l.classroom.building,
            l.classroom.classroom_number,
            l.classroom.seats,
            parseClassroomType(l.classroom.type),
          ),
          new Group(l.group.name, l.group.students_count),
        );
      });

      return new StudyDay(lessons, this.parseDate(day.date));
    });
  }

  private parseDate(dateStr: string): Date {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d);
  }
}
