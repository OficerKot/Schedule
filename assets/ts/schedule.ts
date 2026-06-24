import { StudyDay } from "./classes/model/StudyDay.js";
import { ScheduleViewModel } from "./classes/viewmodel/ScheduleViewModel.js";
import { createStudyDay } from "./classes/view/StudyDayView.js";
import { getMonday } from "./helpers/formatDate.js";
import "./admin.js";

const scheduleVM = new ScheduleViewModel();

// Загружаем справочники при старте
async function init() {
  // Загружаем данные и заполняем фильтры
  const teachers = await scheduleVM.data.getTeachersData();
  const groups = await scheduleVM.data.getGroupsData();
  const classrooms = await scheduleVM.data.getClassroomsData();

  // Заполняем select фильтров
  const teacherSelect = document.getElementById("teacherFilter") as HTMLSelectElement;
  const groupSelect = document.getElementById("groupFilter") as HTMLSelectElement;
  const classroomSelect = document.getElementById("classroomFilter") as HTMLSelectElement;

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
    const teacher = val ? teachers.find(t => String(t.id) === val) || null : null;
    scheduleVM.onTeacherFilterSelect(teacher);
    scheduleVM.showCurrentSavedWeek();
  });

  groupSelect?.addEventListener("change", () => {
    const val = groupSelect.value;
    const group = val ? groups.find(g => g.name === val) || null : null;
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
    const classroom = classrooms.find(c => c.building + c.classroomNumber === val) || null;
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
  scheduleVM.showCurWeek();
}

init();
