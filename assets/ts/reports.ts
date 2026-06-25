// assets/ts/reports.ts

// ==========================================
// ТИПЫ ДАННЫХ ДЛЯ ОТЧЕТОВ
// ==========================================

interface GroupScheduleRow {
    day: string;
    period: string;
    discipline_name: string;
    lesson_type: string;
    teacher: string;
    room_number: string;
    room_type: string;
}

interface MatrixData {
    groups: string[];
    days: string[];
    periods: number[];
    matrix: { [groupName: string]: { [key: string]: string } };
}

interface ExamRow {
    date: string;
    day: string;
    discipline_name: string;
    type: string;
    teacher: string;
    room_number: string;
}

interface WorkloadRow {
    teacher: string;
    totalHours: number;
    lectureHours: number;
    practiceHours: number;
    labHours: number;
}

interface ClassroomUsage {
    totalRooms: number;
    usedRooms: number;
    usagePercent: number;
    byType: { [key: string]: number };
    byDay: { [key: string]: number };
    byPeriod: { [key: string]: number };
}

// ==========================================
// ОСНОВНОЙ КЛАСС ДЛЯ УПРАВЛЕНИЯ ОТЧЕТАМИ НА ФРОНТЕНДЕ
// ==========================================

class ReportsController {
    private loadBtn: HTMLElement | null;
    private content: HTMLElement | null;
    private groupSelect: HTMLSelectElement | null;
    private weekSelect: HTMLSelectElement | null;
    private semesterSelect: HTMLSelectElement | null;
    private subdivisionSelect: HTMLSelectElement | null;
    private buildingSelect: HTMLSelectElement | null;
    private reportType: string;

    constructor() {
        this.loadBtn = document.getElementById('loadReportBtn');
        this.content = document.getElementById('reportContent');
        this.groupSelect = document.getElementById('groupSelect') as HTMLSelectElement | null;
        this.weekSelect = document.getElementById('weekSelect') as HTMLSelectElement | null;
        this.semesterSelect = document.getElementById('semesterSelect') as HTMLSelectElement | null;
        this.subdivisionSelect = document.getElementById('subdivisionSelect') as HTMLSelectElement | null;
        this.buildingSelect = document.getElementById('buildingSelect') as HTMLSelectElement | null;
        this.reportType = this.getReportTypeFromUrl();

        this.init();
    }

    private init(): void {
        if (this.loadBtn) {
            this.loadBtn.addEventListener('click', () => {
                this.handleLoadClick();
            });
        }

        // Если есть кнопка "Назад" — обрабатываем
        const backBtn = document.querySelector('.back-btn');
        if (backBtn) {
            backBtn.addEventListener('click', () => {
                window.location.href = 'reports.php';
            });
        }
    }

    private getReportTypeFromUrl(): string {
        const path = window.location.pathname;
        if (path.includes('group_schedule')) return 'group_schedule';
        if (path.includes('matrix')) return 'matrix';
        if (path.includes('exams')) return 'exams';
        if (path.includes('workload')) return 'workload';
        if (path.includes('classrooms')) return 'classrooms';
        return 'group_schedule';
    }

    private handleLoadClick(): void {
        let groupId = this.groupSelect ? parseInt(this.groupSelect.value) : 0;
        const week = this.weekSelect ? parseInt(this.weekSelect.value) : 1;
        const semesterId = this.semesterSelect ? parseInt(this.semesterSelect.value) : 0;
        const subdivisionId = this.subdivisionSelect ? parseInt(this.subdivisionSelect.value) : 0;
        const building = this.buildingSelect ? this.buildingSelect.value : '';
        let groupIds: number[] | null = null;

        // Для шахматки – собираем массив выбранных групп
        if (this.reportType === 'matrix' && this.groupSelect && this.groupSelect.multiple) {
            groupIds = Array.from(this.groupSelect.selectedOptions).map(opt => parseInt(opt.value));
            groupId = 0; // groupId не используем
        }

        this.loadReport(this.reportType, groupId, week, semesterId, subdivisionId, building, groupIds);
    }

    private loadReport(
        type: string,
        groupId: number,
        week: number,
        semesterId: number,
        subdivisionId: number,
        building: string,
        groupIds: number[] | null
    ): void {
        if (this.content) {
            this.content.innerHTML = '<div class="loading">Загрузка...</div>';
        }

        const params = new URLSearchParams();
        params.set('type', type);
        if (groupId) params.set('group_id', String(groupId));
        if (week) params.set('week', String(week));
        if (semesterId) params.set('semester_id', String(semesterId));
        if (subdivisionId) params.set('subdivision_id', String(subdivisionId));
        if (building) params.set('building', building);
        if (groupIds && groupIds.length) {
            params.set('group_ids', groupIds.join(','));
        }

        fetch(`../api/get_report_data.php?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then((result: any) => {
                if (result.success) {
                    this.renderReport(type, result.data);
                } else {
                    if (this.content) {
                        this.content.innerHTML = `<div class="error">${result.message || 'Ошибка загрузки'}</div>`;
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                if (this.content) {
                    this.content.innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
                }
            });
    }

    private renderReport(type: string, data: any): void {
        if (!this.content) return;

        switch (type) {
            case 'group_schedule':
                this.renderGroupSchedule(data);
                break;
            case 'exams':
                this.renderExams(data);
                break;
            case 'matrix':
                this.renderMatrix(data);
                break;
            case 'workload':
                this.renderWorkload(data);
                break;
            case 'classrooms':
                this.renderClassrooms(data);
                break;
            default:
                this.content.innerHTML = `<div class="info">${data?.message || 'Данные загружены'}</div>`;
        }
    }

    // ==========================================
    // ОТРИСОВКА ОТЧЕТОВ
    // ==========================================

    private renderGroupSchedule(data: GroupScheduleRow[]): void {
        if (!this.content) return;

        if (!data || data.length === 0) {
            this.content.innerHTML = '<div class="empty">Нет данных для отображения</div>';
            return;
        }

        let html = `
            <table class="report-table">
                <thead>
                    <tr>
                        <th>День</th>
                        <th>Пара</th>
                        <th>Дисциплина</th>
                        <th>Тип</th>
                        <th>Преподаватель</th>
                        <th>Аудитория</th>
                        <th>Тип аудитории</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach(row => {
            html += `
                <tr>
                    <td>${row.day || ''}</td>
                    <td>${row.period || ''}</td>
                    <td>${row.discipline_name || ''}</td>
                    <td><span class="type-badge">${row.lesson_type || ''}</span></td>
                    <td>${row.teacher || ''}</td>
                    <td>${row.room_number || ''}</td>
                    <td>${row.room_type || ''}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            <div class="export-buttons">
                <button onclick="alert('PDF экспорт будет позже')">📄 PDF</button>
                <button onclick="alert('Excel экспорт будет позже')">📊 Excel</button>
            </div>
        `;

        this.content.innerHTML = html;
    }

    private renderExams(data: ExamRow[]): void {
        if (!this.content) return;

        if (!data || data.length === 0) {
            this.content.innerHTML = '<div class="empty">Нет зачетов/экзаменов</div>';
            return;
        }

        let html = `
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>День</th>
                        <th>Дисциплина</th>
                        <th>Тип</th>
                        <th>Преподаватель</th>
                        <th>Аудитория</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach(row => {
            html += `
                <tr>
                    <td>${row.date || ''}</td>
                    <td>${row.day || ''}</td>
                    <td>${row.discipline_name || ''}</td>
                    <td><span class="type-badge ${row.type}">${row.type || ''}</span></td>
                    <td>${row.teacher || ''}</td>
                    <td>${row.room_number || ''}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            <div class="export-buttons">
                <button onclick="alert('PDF экспорт будет позже')">📄 PDF</button>
                <button onclick="alert('Excel экспорт будет позже')">📊 Excel</button>
            </div>
        `;

        this.content.innerHTML = html;
    }

    private renderMatrix(data: MatrixData): void {
        if (!this.content) return;

        if (!data || !data.groups || data.groups.length === 0) {
            this.content.innerHTML = '<div class="empty">Нет данных для шахматки</div>';
            return;
        }

        const { groups, days, periods, matrix } = data;

        let html = `
            <div class="matrix-container">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="fixed-col">Группа</th>
        `;

        days.forEach(day => {
            periods.forEach(p => {
                html += `<th>${day}<br><small>${p}</small></th>`;
            });
        });

        html += `</tr></thead><tbody>`;

        groups.forEach(group => {
            html += `<tr><td class="fixed-col"><strong>${group}</strong></td>`;
            days.forEach(day => {
                periods.forEach(p => {
                    const key = `${day}_${p}`;
                    const value = matrix[group]?.[key] || '';
                    const cls = value ? 'occupied' : 'free';
                    html += `<td class="${cls}">${value || '·'}</td>`;
                });
            });
            html += `</tr>`;
        });

        html += `
                </tbody></table>
            </div>
            <div class="matrix-legend">
                <span class="legend-item"><span class="occupied-box"></span> Занято</span>
                <span class="legend-item"><span class="free-box"></span> Свободно (окно)</span>
            </div>
            <div class="export-buttons">
                <button onclick="alert('PDF экспорт будет позже')">📄 PDF</button>
                <button onclick="alert('Excel экспорт будет позже')">📊 Excel</button>
            </div>
        `;

        this.content.innerHTML = html;
    }

    private renderWorkload(data: WorkloadRow[]): void {
        if (!this.content) return;

        if (!data || data.length === 0) {
            this.content.innerHTML = '<div class="empty">Нет данных о нагрузке</div>';
            return;
        }

        let html = `
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Преподаватель</th>
                        <th>Всего часов</th>
                        <th>Лекции</th>
                        <th>Практики</th>
                        <th>Лабораторные</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach(row => {
            html += `
                <tr>
                    <td>${row.teacher || ''}</td>
                    <td>${row.totalHours || 0}</td>
                    <td>${row.lectureHours || 0}</td>
                    <td>${row.practiceHours || 0}</td>
                    <td>${row.labHours || 0}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            <div class="export-buttons">
                <button onclick="alert('PDF экспорт будет позже')">📄 PDF</button>
                <button onclick="alert('Excel экспорт будет позже')">📊 Excel</button>
            </div>
        `;

        this.content.innerHTML = html;
    }

    private renderClassrooms(data: ClassroomUsage): void {
        if (!this.content) return;

        if (!data || typeof data.totalRooms === 'undefined') {
            this.content.innerHTML = '<div class="empty">Нет данных о помещениях</div>';
            return;
        }

        let html = `
            <div class="classroom-stats">
                <div class="stat-item"><strong>Всего аудиторий:</strong> ${data.totalRooms}</div>
                <div class="stat-item"><strong>Используется:</strong> ${data.usedRooms}</div>
                <div class="stat-item"><strong>Загруженность:</strong> ${data.usagePercent}%</div>
            </div>
        `;

        if (data.byType && Object.keys(data.byType).length) {
            html += `<h4>Занятость по типам</h4><ul>`;
            for (const [type, percent] of Object.entries(data.byType)) {
                html += `<li>${type}: ${percent}%</li>`;
            }
            html += `</ul>`;
        }

        if (data.byDay && Object.keys(data.byDay).length) {
            html += `<h4>Занятость по дням</h4><ul>`;
            for (const [day, count] of Object.entries(data.byDay)) {
                html += `<li>${day}: ${count} аудиторий</li>`;
            }
            html += `</ul>`;
        }

        if (data.byPeriod && Object.keys(data.byPeriod).length) {
            html += `<h4>Занятость по парам</h4><ul>`;
            for (const [period, count] of Object.entries(data.byPeriod)) {
                html += `<li>${period} пара: ${count} аудиторий</li>`;
            }
            html += `</ul>`;
        }

        html += `
            <div class="export-buttons">
                <button onclick="alert('PDF экспорт будет позже')">📄 PDF</button>
                <button onclick="alert('Excel экспорт будет позже')">📊 Excel</button>
            </div>
        `;

        this.content.innerHTML = html;
    }
}

// ==========================================
// ЗАПУСК ПРИ ЗАГРУЗКЕ DOM
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    new ReportsController();
});