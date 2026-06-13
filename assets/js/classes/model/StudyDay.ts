/** Учебный день, хранящий в себе список занятий */

class StudyDay {
  constructor(
    private lessons: Lesson[],
    public readonly date: Date,
  ) {}

  addLesson(lesson: Lesson) {
    this.lessons.push(lesson);
  }

  getLessons() {
    return [...this.lessons];
  }
}
