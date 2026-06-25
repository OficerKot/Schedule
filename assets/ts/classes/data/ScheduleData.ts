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
    const res = await fetch("../api/get_teachers.php");
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
    const res = await fetch("../api/get_groups.php");
    const data = await res.json();
    this.groups = data.map((g: any) => new Group(g.name, g.students_count, g.group_id));
    return this.groups;
  }

  async getClassroomsData(): Promise<Classroom[]> {
    const res = await fetch("../api/get_rooms.php");
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
    const params = new URLSearchParams();
    params.set("monday", monday.toISOString().split("T")[0]);

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

    const res = await fetch("../api/get_schedule.php?" + params.toString());
    const data = await res.json();

    // Группируем занятия по дате
    const daysMap = new Map<string, any[]>();
    data.forEach((lesson: any) => {
      const dateKey = lesson.semester_date;
      if (!daysMap.has(dateKey)) {
        daysMap.set(dateKey, []);
      }
      daysMap.get(dateKey)!.push(lesson);
    });

    return Array.from(daysMap.entries()).map(([dateStr, lessons]) => {
      const parsedLessons = lessons.map((l: any) => {
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
          l.discipline_name,
          parseInt(l.period_number),
          lessonType,
          new Teacher(
            l.teacher_id,
            l.teacher_name.split(' ')[1] || "",
            l.teacher_name.split(' ')[2] || "",
            l.teacher_name.split(' ')[0] || "",
            "", "", "", "", undefined, undefined,
          ),
          new Classroom(
            l.building,
            l.room_number,
            parseInt(l.seats) || 0,
            parseClassroomType(l.room_type),
          ),
          new Group(l.group_name, 0),
        );
      });

      return new StudyDay(parsedLessons, this.parseDate(dateStr));
    });
  }

  private parseDate(dateStr: string): Date {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d);
  }
}
