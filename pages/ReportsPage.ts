// src/pages/ReportsPage.ts

export class ReportsPage {
  private container: HTMLElement;

  constructor(container: HTMLElement) {
    this.container = container;
  }

  render(): void {
    this.container.innerHTML = `
            <div class="reports-page">
                <h1> Отчеты</h1>
                <p>Выберите тип отчета:</p>
                
                <div class="reports-grid">
                    <div class="report-card" data-report="group-schedule">
                        <span class="icon"></span>
                        <h3>Расписание группы</h3>
                        <p>Печатная форма расписания для одной группы</p>
                    </div>
                    
                    <div class="report-card" data-report="matrix">
                        <span class="icon"></span>
                        <h3>Шахматная ведомость</h3>
                        <p>Расписание нескольких групп в виде шахматки</p>
                    </div>
                    
                    <div class="report-card" data-report="exams">
                        <span class="icon"></span>
                        <h3>Зачеты и экзамены</h3>
                        <p>Список зачетов и экзаменов для группы</p>
                    </div>
                    
                    <div class="report-card" data-report="workload">
                        <span class="icon"></span>
                        <h3>Нагрузка преподавателей</h3>
                        <p>План занятости преподавателей подразделения</p>
                    </div>
                    
                    <div class="report-card" data-report="classrooms">
                        <span class="icon"></span>
                        <h3>Занятость помещений</h3>
                        <p>Сводная занятость по типам, времени, корпусам</p>
                    </div>
                </div>
            </div>
        `;

    // Навешиваем обработчики на карточки
    this.container.querySelectorAll(".report-card").forEach((card) => {
      card.addEventListener("click", () => {
        const reportType = card.getAttribute("data-report");
        if (reportType) {
          this.navigateToReport(reportType);
        }
      });
    });
  }

  private navigateToReport(reportType: string): void {
    const event = new CustomEvent("navigate", {
      detail: { page: "report", reportType },
    });
    document.dispatchEvent(event);
  }
}
