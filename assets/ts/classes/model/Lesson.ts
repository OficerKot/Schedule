import { Teacher } from "./Teacher.js";
import { Classroom } from "./Classroom.js";
import { Group } from "./Group.js";

/** Тип занятия */
export enum LessonType {
  LECTION = "лекция",
  PRACTICE = "практика",
  LAB = "лабораторные работы",
  CREDIT = "зачёт",
  EXAM = "экзамен",
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
