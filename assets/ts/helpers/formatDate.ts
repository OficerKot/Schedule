import { WEEK_DAYS } from "../../../constants.js";

export function formatDateShort(date: Date): string {
  const day = date.getDate();

  let month = date.getMonth() + 1;
  const monthString = month < 10 ? `0${month}` : String(month);

  const weekDay = WEEK_DAYS[date.getDay()];
  return `${weekDay}, ${day}.${monthString}`;
}

/** Возвращает дату понедельника текущей недели */
export function getMonday(date: Date): Date {
  const curDay = date.getDay();
  const diff = (curDay + 6) % 7;
  const monday = new Date(date);
  monday.setDate(date.getDate() - diff);
  return monday;
}
