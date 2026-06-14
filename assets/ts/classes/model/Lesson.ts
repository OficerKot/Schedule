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

//Для проверки отображения карточек. Потом удалить
export const testLesson1: Lesson = new Lesson(
  "Аналитическая геометрия и компьютерная графика",
  1,
  LessonType.LECTION,
  new Teacher(
    101,
    "Ирина",
    "Алексеевна",
    "Курочкина",
    "ИМКТ",
    "position",
    "kafedra",
    "Департамент математического и компьютерного моделирования",
  ),
  new Classroom("D", "738", 150, ClassroomType.LECTURE),
  new Group("Б9124-09.03.03 пикд", 28),
);

export const testLesson2: Lesson = new Lesson(
  "Математический анализ",
  1,
  LessonType.LECTION,
  new Teacher(
    102,
    "Юрий",
    "Александрович",
    "Клевчихин",
    "ИМКТ",
    "position",
    "kafedra",
    "Департамент математики",
  ),
  new Classroom("D", "738", 150, ClassroomType.LECTURE),
  new Group("Б9124-09.03.03 пикд", 28),
);
