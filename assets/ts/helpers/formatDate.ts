import { WEEK_DAYS, MONDAY } from "../constants.js";

export function formatDateShort(date: Date): string {
  const day = date.getDate();

  const month = date.getMonth() + 1;
  const monthString = month < 10 ? `0${month}` : String(month);

  const weekDay = WEEK_DAYS[date.getDay()];
  return `${weekDay}, ${day}.${monthString}`;
}

/** Возвращает дату понедельника текущей недели */
export function getMonday(date: Date): Date {
  const day = date.getDay();
  const diff = (day - MONDAY + 7) % 7;

  const thisMonday = new Date(date);
  thisMonday.setDate(date.getDate() - diff);
  return thisMonday;
}
