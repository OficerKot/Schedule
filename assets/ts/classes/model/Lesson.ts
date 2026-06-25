import { Teacher } from "./Teacher.js";
import { Classroom, ClassroomType } from "./Classroom.js";
import { Group } from "./Group.js";

/** Тип занятия */
export enum LessonType {
  LECTION = "Лекция",
  PRACTICE = "Практика",
  LAB = "Лабораторные работы",
  CREDIT = "Зачёт",
  EXAM = "Экзамен",
}

/** Хранит информацию об одном учебном занятии */
export class Lesson {
  constructor(
    public readonly lessonName: string,
    public readonly lessonNumber: number,
    public readonly lessonType: LessonType,
    public readonly teacher: Teacher,
    public readonly classroom: Classroom,
    public readonly group: Group,
  ) {}

  isOverlapWith(lesson: Lesson): boolean {
    return (
      this.lessonNumber === lesson.lessonNumber &&
      (this.teacher.id === lesson.teacher.id ||
        this.group.name === lesson.group.name)
    );
  }
}

