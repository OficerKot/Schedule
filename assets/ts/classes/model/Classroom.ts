export enum ClassroomType {
  LECTURE = "lecture",
  COMPUTER = "computer",
  LAB = "lab",
  SEMINAR = "practical",
}

export function parseClassroomType(value: string): ClassroomType {
  return ClassroomType[value as keyof typeof ClassroomType] || ClassroomType.LECTURE;
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
