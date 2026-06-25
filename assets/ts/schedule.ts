import { ScheduleViewModel } from "./classes/viewmodel/ScheduleViewModel.js";
import { getMonday } from "./helpers/formatDate.js"; // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
import "./admin.js";

const scheduleVM = new ScheduleViewModel();

// Функция для получения номера недели
function getWeekNumber(date: Date): number {
  const startOfYear = new Date(date.getFullYear(), 0, 1);
  const diff = date.getTime() - startOfYear.getTime();
  return Math.ceil((diff / 86400000 + startOfYear.getDay() + 1) / 7);
}

// Функция обновления информации о неделе
function updateWeekInfo(monday: Date) {
  const weekInfo = document.querySelector(".week-info");
  if (!weekInfo) return;

  const weekNum = getWeekNumber(monday);
  const parity = weekNum % 2 === 0 ? "чётная" : "нечётная";
  const endOfWeek = new Date(monday);
  endOfWeek.setDate(endOfWeek.getDate() + 6);

  const monthNames = [
    "января",
    "февраля",
    "марта",
    "апреля",
    "мая",
    "июня",
    "июля",
    "августа",
    "сентября",
    "октября",
    "ноября",
    "декабря",
  ];

  weekInfo.innerHTML = `
    <span class="week-number">${weekNum} неделя</span>
    <span style="color: #50596e;">|</span>
    ${monday.getDate()} ${monthNames[monday.getMonth()]} – ${endOfWeek.getDate()} ${monthNames[endOfWeek.getMonth()]}
    <span style="color: #50596e;">|</span>
    <span class="week-parity ${parity === "чётная" ? "even" : "odd"}">${parity}</span>
  `;
}

// Переопределяем методы show для обновления информации о неделе
const originalShowNextWeek = scheduleVM.showNextWeek.bind(scheduleVM);
scheduleVM.showNextWeek = async function () {
  await originalShowNextWeek();
  updateWeekInfo(this["curWeekDate"]);
};

const originalShowPrevWeek = scheduleVM.showPrevWeek.bind(scheduleVM);
scheduleVM.showPrevWeek = async function () {
  await originalShowPrevWeek();
  updateWeekInfo(this["curWeekDate"]);
};

const originalShowCurWeek = scheduleVM.showCurWeek.bind(scheduleVM);
scheduleVM.showCurWeek = async function () {
  await originalShowCurWeek();
  const monday = getMonday(this["curWeekDate"]);
  updateWeekInfo(monday);
};

// Загружаем справочники при старте
async function init() {
  // Добавляем информацию о неделе
  const navContainer = document.querySelector(".navButtonsContainer");
  if (navContainer) {
    let weekInfo = navContainer.querySelector(".week-info");
    if (!weekInfo) {
      weekInfo = document.createElement("div");
      weekInfo.className = "week-info";
      const navButtons = navContainer.querySelector(".nav-buttons");
      if (navButtons) {
        navContainer.insertBefore(weekInfo, navButtons);
      } else {
        navContainer.appendChild(weekInfo);
      }
    }
  }

  // Загружаем данные и заполняем фильтры
  const teachers = await scheduleVM.data.getTeachersData();
  const groups = await scheduleVM.data.getGroupsData();
  const classrooms = await scheduleVM.data.getClassroomsData();

  // Заполняем select фильтров
  const teacherSelect = document.getElementById(
    "teacherFilter",
  ) as HTMLSelectElement;
  const groupSelect = document.getElementById(
    "groupFilter",
  ) as HTMLSelectElement;
  const classroomSelect = document.getElementById(
    "classroomFilter",
  ) as HTMLSelectElement;

  teachers.forEach((t) => {
    const opt = document.createElement("option");
    opt.value = String(t.id);
    opt.textContent = `${t.last_name} ${t.first_name.charAt(0)}.${t.middle_name.charAt(0)}.`;
    teacherSelect?.appendChild(opt);
  });

  groups.forEach((g) => {
    const opt = document.createElement("option");
    opt.value = g.name;
    opt.textContent = g.name;
    opt.dataset.students = String(g.studentCount);
    groupSelect?.appendChild(opt);
  });

  classrooms.forEach((c) => {
    const opt = document.createElement("option");
    opt.value = c.building + c.classroomNumber;
    opt.textContent = `${c.building}-${c.classroomNumber} (${c.seats} мест, ${c.type})`;
    opt.dataset.building = c.building;
    opt.dataset.number = c.classroomNumber;
    classroomSelect?.appendChild(opt);
  });

  // Привязываем обработчики фильтров
  teacherSelect?.addEventListener("change", () => {
    const val = teacherSelect.value;
    const teacher = val
      ? teachers.find((t) => String(t.id) === val) || null
      : null;
    scheduleVM.onTeacherFilterSelect(teacher);
    scheduleVM.showCurrentSavedWeek();
  });

  groupSelect?.addEventListener("change", () => {
    const val = groupSelect.value;
    const group = val ? groups.find((g) => g.name === val) || null : null;
    scheduleVM.onGroupFilterSelect(group);
    scheduleVM.showCurrentSavedWeek();
  });

  classroomSelect?.addEventListener("change", () => {
    const val = classroomSelect.value;
    if (!val) {
      scheduleVM.onClassroomFilterSelect(null);
      scheduleVM.showCurrentSavedWeek();
      return;
    }
    const classroom =
      classrooms.find((c) => c.building + c.classroomNumber === val) || null;
    scheduleVM.onClassroomFilterSelect(classroom);
    scheduleVM.showCurrentSavedWeek();
  });

  // Кнопки навигации
  document.getElementById("nextWeekBtn")?.addEventListener("click", () => {
    scheduleVM.showNextWeek();
  });

  document.getElementById("prevWeekBtn")?.addEventListener("click", () => {
    scheduleVM.showPrevWeek();
  });

  document.getElementById("curWeekBtn")?.addEventListener("click", () => {
    scheduleVM.showCurWeek();
  });

  // Показываем первую неделю
  await scheduleVM.showCurWeek();

  // Обновляем информацию о неделе
  const monday = getMonday(scheduleVM["curWeekDate"]);
  updateWeekInfo(monday);
}

init();
