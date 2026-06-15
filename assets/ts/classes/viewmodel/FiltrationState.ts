import { Classroom } from "../model/Classroom";
import { Group } from "../model/Group";
import { Teacher } from "../model/Teacher";

//**Хранит в себе выбранные фильтры */
export class FiltrationState {
  teacher: Teacher | null = null;
  group: Group | null = null;
  classroom: Classroom | null = null;

  //**Сброс фильтров */
  reset() {
    this.teacher = null;
    this.group = null;
    this.classroom = null;
  }

  //**Проверка на наличие выбранных фильтров */
  isActive(): boolean {
    return (
      this.teacher !== null || this.group !== null || this.classroom !== null
    );
  }
}
