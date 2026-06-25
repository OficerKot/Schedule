// src/services/ReportService.ts

import { ScheduleData } from '../data/ScheduleData.js';
import { FiltrationState } from '../viewmodel/FiltrationState.js';  // 👈 ДОБАВЛЯЕМ ИМПОРТ

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
    [key: string]: string;
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
        // Получаем группу (нужен метод getGroupById)
        const group = await this.dataProvider.getGroupById(groupId);
        if (!group) return [];

        // Создаем фильтр
        const filters = new FiltrationState();
        filters.group = group;

        // Вычисляем понедельник нужной недели
        const monday = this.getMondayOfWeek(weekNumber);

        // ✅ ПЕРЕДАЕМ 2 АРГУМЕНТА: filters И monday
        const studyDays = await this.dataProvider.getStudyDays(filters, monday);

        const result: GroupScheduleRow[] = [];
        for (const day of studyDays) {
            for (const lesson of day.getLessons()) {
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
        const days = ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'];
        const periods = ['1', '2', '3', '4', '5', '6', '7', '8'];
        const matrix: { [key: string]: MatrixRow } = {};
        const groupNames: string[] = [];

        const monday = this.getMondayOfWeek(weekNumber);

        for (const groupId of groupIds) {
            const group = await this.dataProvider.getGroupById(groupId);
            if (!group) continue;

            groupNames.push(group.name);

            const filters = new FiltrationState();
            filters.group = group;

            // ✅ ПЕРЕДАЕМ 2 АРГУМЕНТА: filters И monday
            const studyDays = await this.dataProvider.getStudyDays(filters, monday);

            const row: MatrixRow = {};
            for (const day of studyDays) {
                for (const lesson of day.getLessons()) {
                    const key = `${day.date.getDay()}_${lesson.lessonNumber}`;
                    row[key] = lesson.lessonName;
                }
            }

            matrix[group.name] = row;
        }

        return {
            groups: groupNames,
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

    // ==========================================
    // Вспомогательные методы
    // ==========================================

    private getMondayOfWeek(weekNumber: number): Date {
        // Базовая дата: 1 сентября 2026 года (начало семестра)
        const baseDate = new Date(2026, 8, 1); // 1 сентября 2026
        // Сдвиг на (weekNumber - 1) недель
        const monday = new Date(baseDate);
        monday.setDate(monday.getDate() + (weekNumber - 1) * 7);
        return monday;
    }
}