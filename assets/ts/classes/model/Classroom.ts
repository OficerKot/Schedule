export enum ClassroomType {
  LECTURE = "лекционная",
  COMPUTER = "компьютерный класс",
  LAB = "лаборатория",
  SEMINAR = "семинарская",
}

/** Аудитория */
export class Classroom {
  constructor(
    public readonly building: string,
    public readonly classroomNumber: string,
    public readonly seats: number,
    public readonly type: ClassroomType,
  ) {}
}
