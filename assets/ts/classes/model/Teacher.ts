/** Преподаватель */
export class Teacher {
  constructor(
    public readonly id: number,
    public readonly first_name: string,
    public readonly middle_name: string,
    public readonly last_name: string,
    public readonly school: string,
    public readonly position: string,
    public readonly kafedra: string,
    public readonly department: string,
    public readonly degree?: string,
    public readonly academicTitle?: string,
  ) {}
}
