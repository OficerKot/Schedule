//** Преподаватель */
export class Teacher {
  constructor(
    public readonly id: number,
    public readonly first_name: string,
    public readonly middle_name: string,
    public readonly last_name: string,
    public readonly school: string,
    public readonly position: string, // должность
    public readonly kafedra: string,
    public readonly department: string,
    public readonly degree?: string, //учёная степень
    public readonly academicTitle?: string, //учёное звание
  ) {}
}

// Из закрепа:
// Информация о препода: ФИО, школа, департамент, кафедра, при наличии ученая степень и учёное звание, должность
// В электронном расписании по хорошему указать всё
// У каждого препода есть учебные поручения по ведению предмета. Пары ставим в соответствии с трудовым кодексом
