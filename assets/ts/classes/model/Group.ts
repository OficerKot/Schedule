/** Учебная группа */
export class Group {
  constructor(
    public readonly name: string,
    public readonly studentCount: number,
    public readonly groupId?: number,
  ) {}
}
