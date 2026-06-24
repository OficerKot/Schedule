// src/services/ReportService.ts

import { ScheduleData } from '../data/ScheduleData.js';

// Типы данных для отчетов
export interface GroupScheduleRow {
    day: string;
    period: string;
    discipline: string;
    lessonType: string;
    teacher: string;
    room: string;
    roomType: string;
}

export interface MatrixRow {
    [key: string]: string; // "day_period" -> disciplineName
}

export interface ExamRow {
    date: string;
    day: string;
    discipline: string;
    type: string;
    teacher: string;
    room: string;
    durationMinutes: number;
}

export interface TeacherWorkloadRow {
    teacher: string;
    totalHours: number;
    lectureHours: number;
    practiceHours: number;
    labHours: number;
}

export interface ClassroomUsage {
    totalRooms: number;
    usedRooms: number;
    usagePercent: number;
    byType: { [key: string]: number };
    byDay: { [key: string]: number };
    byPeriod: { [key: string]: number };
}

export class ReportService {
    private dataProvider: ScheduleData;

    constructor() {
        this.dataProvider = new ScheduleData();
    }

    // ==========================================
    // Отчет №1: Расписание одной группы
    // ==========================================
    async getGroupSchedule(groupId: number, weekNumber: number = 1): Promise<GroupScheduleRow[]> {
        // Используем существующий метод getStudyDays() из ScheduleData
        // Предполагаем, что FiltrationState содержит groupId и weekNumber
        const filters = {
            groupId: groupId,
            weekNumber: weekNumber,
            // ... другие поля, если нужны
        };
        
        const studyDays = await this.dataProvider.getStudyDays(filters as any);
        const result: GroupScheduleRow[] = [];
        
        // Преобразуем StudyDay[] в GroupScheduleRow[]
        // Это временная заглушка, пока getStudyDays() не возвращает реальные данные
        // Замените на реальную логику, когда getStudyDays() будет готов
        
        // Пример преобразования (адаптируйте под свою структуру)
        for (const day of studyDays) {
            for (const lesson of day.lessons) {
                result.push({
                    day: day.date.toLocaleDateString('ru-RU', { weekday: 'short' }),
                    period: `${lesson.lessonNumber} пара`,
                    discipline: lesson.lessonName,
                    lessonType: lesson.lessonType.toString(),
                    teacher: `${lesson.teacher.last_name} ${lesson.teacher.first_name}`,
                    room: lesson.classroom.classroomNumber,
                    roomType: lesson.classroom.type
                });
            }
        }
        
        return result;
    }

    // ==========================================
    // Отчет №2: Шахматная ведомость
    // ==========================================
    async getMatrixSchedule(groupIds: number[], weekNumber: number = 1): Promise<{
        groups: string[];
        days: string[];
        periods: string[];
        matrix: { [groupName: string]: MatrixRow };
    }> {
        // Заглушка: возвращает тестовые данные
        // Здесь нужно будет сделать несколько запросов к getStudyDays() для каждой группы
        
        const days = ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'];
        const periods = ['1', '2', '3', '4', '5', '6', '7', '8'];
        const matrix: { [key: string]: MatrixRow } = {};
        
        for (const groupId of groupIds) {
            // Получаем данные для группы
            const filters = { groupId, weekNumber };
            const studyDays = await this.dataProvider.getStudyDays(filters as any);
            
            // Заполняем матрицу
            const groupName = `Группа ${groupId}`;
            const row: MatrixRow = {};
            
            for (const day of studyDays) {
                for (const lesson of day.lessons) {
                    const key = `${day.date.getDay()}_${lesson.lessonNumber}`;
                    row[key] = lesson.lessonName;
                }
            }
            
            matrix[groupName] = row;
        }
        
        return {
            groups: groupIds.map(id => `Группа ${id}`),
            days,
            periods,
            matrix
        };
    }

    // ==========================================
    // Отчет №3: Зачеты и экзамены
    // ==========================================
    async getExamsAndCredits(groupId: number, semesterId: number): Promise<ExamRow[]> {
        // Заглушка
        return [
            { date: '10.01.2027', day: 'ПН', discipline: 'Математический анализ', type: 'Экзамен', teacher: 'Курочкина И.А.', room: '101', durationMinutes: 560 },
            { date: '15.01.2027', day: 'СБ', discipline: 'Программирование на C++', type: 'Экзамен', teacher: 'Смирнов А.П.', room: '303', durationMinutes: 560 },
            { date: '20.01.2027', day: 'ЧТ', discipline: 'Базы данных', type: 'Зачёт', teacher: 'Петрова М.И.', room: '201', durationMinutes: 280 },
            { date: '22.01.2027', day: 'СБ', discipline: 'История России', type: 'Зачёт', teacher: 'Сидорова Е.С.', room: '102', durationMinutes: 200 },
        ];
    }

    // ==========================================
    // Отчет №4: Нагрузка преподавателей
    // ==========================================
    async getTeachersWorkload(subdivisionId: number, semesterId?: number): Promise<TeacherWorkloadRow[]> {
        // Заглушка
        return [
            { teacher: 'Курочкина И.А.', totalHours: 120, lectureHours: 60, practiceHours: 30, labHours: 30 },
            { teacher: 'Клевчихин Ю.А.', totalHours: 90, lectureHours: 50, practiceHours: 40, labHours: 0 },
            { teacher: 'Смирнов А.П.', totalHours: 150, lectureHours: 40, practiceHours: 20, labHours: 90 },
            { teacher: 'Петрова М.И.', totalHours: 80, lectureHours: 20, practiceHours: 10, labHours: 50 },
            { teacher: 'Иванов В.Н.', totalHours: 100, lectureHours: 60, practiceHours: 40, labHours: 0 },
            { teacher: 'Козлов Д.В.', totalHours: 70, lectureHours: 20, practiceHours: 30, labHours: 20 },
        ];
    }

    // ==========================================
    // Отчет №5: Сводная занятость помещений
    // ==========================================
    async getClassroomsUsage(semesterId?: number, buildingId?: number): Promise<ClassroomUsage> {
        // Заглушка
        return {
            totalRooms: 11,
            usedRooms: 8,
            usagePercent: 72.7,
            byType: {
                'Лекционная': 80,
                'Практическая': 60,
                'Лаборатория': 75,
                'Компьютерный класс': 80,
            },
            byDay: {
                'ПН': 6,
                'ВТ': 5,
                'СР': 4,
                'ЧТ': 6,
                'ПТ': 3,
                'СБ': 1,
            },
            byPeriod: {
                '1 пара (8:30-10:00)': 4,
                '2 пара (10:10-11:40)': 5,
                '3 пара (11:50-13:20)': 3,
                '4 пара (13:30-15:00)': 2,
                '5 пара (15:10-16:40)': 1,
            }
        };
    }
}