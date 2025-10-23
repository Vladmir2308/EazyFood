export function getCurrentWeekDates() {
    const weekdays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']

    const today = new Date()
    const day = today.getDay() // 0 (вс) — 6 (сб)

    // Сдвигаем дату назад к понедельнику
    const monday = new Date()
    const diffToMonday = (day === 0 ? -6 : 1 - day)
    monday.setDate(today.getDate() + diffToMonday)

    // Формируем неделю от понедельника
    const result = []
    for (let i = 0; i < 7; i++) {
        const d = new Date(monday)
        d.setDate(monday.getDate() + i)
        result.push({
            name: weekdays[i],
            date: d.toISOString().slice(0, 10)
        })
    }

    return result
}
