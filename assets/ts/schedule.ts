import { StudyDay } from "./classes/model/StudyDay.js";
import { ScheduleViewModel } from "./classes/viewmodel/ScheduleViewModel.js";
import { createStudyDay } from "./classes/view/StudyDayView.js";
import { getMonday } from "./helpers/formatDate.js";
import { ScheduleData } from "./classes/data/ScheduleData.js";

const data = new ScheduleData();
const scheduleVM = new ScheduleViewModel(data);
scheduleVM.showCurWeek();

document.getElementById("nextWeekBtn")?.addEventListener("click", () => {
  scheduleVM.showNextWeek();
});

document.getElementById("prevWeekBtn")?.addEventListener("click", () => {
  scheduleVM.showPrevWeek();
});

document.getElementById("curWeekBtn")?.addEventListener("click", () => {
  scheduleVM.showCurWeek();
});

document.getElementById("teacherFilter")?.addEventListener("change", (e) => {
  const select = e.target as HTMLSelectElement;
  const teacherId = select.value;

  const teacher =
    data.getTeachersData().find((t) => t.id.toString() === teacherId) || null;

  scheduleVM.onTeacherFilterSelect(teacher);
});
