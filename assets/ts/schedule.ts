import { StudyDay } from "./classes/model/StudyDay.js";
import { ScheduleViewModel } from "./classes/viewmodel/ScheduleViewModel.js";
import { createStudyDay } from "./classes/view/StudyDayView.js";
import { getMonday } from "./helpers/formatDate.js";

const scheduleVM = new ScheduleViewModel();
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
