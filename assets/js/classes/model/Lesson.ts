import { Classroom } from "./Classroom";
import { Group } from "./Group";
import { Teacher } from "./Teacher";

/** Тип занятия  */
enum LessonType {
  LECTION = "лекция",
  PRACTICE = "практическое занятие",
  LAB = "лабораторные работы",
  CREDIT = "зачёт",
  EXAM = "экзамен",
}

//** Хранит информацию об одном учебном занятии */
export class Lesson {
  constructor(
    public readonly lessonName: string,
    public readonly lessonNumber: number,
    public readonly lessonType: LessonType,
    public readonly teacher: Teacher,
    public readonly classroom: Classroom,
    public readonly group: Group,
  ) {}

  isOverlapWith(lesson: Lesson) {
    return (
      this.lessonNumber == lesson.lessonNumber &&
      (this.teacher.id == lesson.teacher.id ||
        this.group.name == lesson.group.name)
    );
  }
}
