import { Lesson } from "./Lesson.js";

/** Учебный день, хранящий в себе список занятий */
export class StudyDay {
  constructor(
    public readonly lessons: Lesson[],
    public readonly date: Date,
  ) {}

  addLesson(lesson: Lesson): void {
    this.lessons.push(lesson);
  }

  getLessons(): Lesson[] {
    return [...this.lessons];
  }
}
