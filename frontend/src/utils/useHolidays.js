import { ref } from 'vue';
import api from '../api'; // their axios instance

const holidays = ref([]);
const isFetching = ref(false);
const lastFetchedYear = ref(null);

export function useHolidays() {
  const fetchHolidays = async (year = new Date().getFullYear()) => {
    // Prevent refetching if already fetched for this year
    if (holidays.value.length > 0 && lastFetchedYear.value === year) {
        return holidays.value;
    }

    isFetching.value = true;
    try {
      let dbHolidays = [];
      try {
        const res = await api.get(`/holidays?year=${year}`);
        if (res.data && res.data.status === 'success') {
          dbHolidays = res.data.data;
        }
      } catch (err) {
        console.warn('Failed to fetch holidays from backend API', err);
      }

      let apiHolidays = [];
      try {
        const nagerRes = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/PH`);
        if (nagerRes.ok) {
          const nagerData = await nagerRes.json();
          apiHolidays = nagerData.map(h => ({ date: h.date, name: h.localName || h.name }));
        }
      } catch (err) {
        console.warn('Failed to fetch holidays from date.nager.at', err);
      }

      const merged = [...dbHolidays];
      const existingDates = new Set(merged.map(h => h.date));
      for (const ah of apiHolidays) {
        if (!existingDates.has(ah.date)) {
          merged.push(ah);
        }
      }

      holidays.value = merged;
      lastFetchedYear.value = year;
    } catch (error) {
      console.error('Failed to fetch holidays', error);
    } finally {
      isFetching.value = false;
    }
    return holidays.value;
  };

  const isHoliday = (dateObj) => {
    const d = new Date(dateObj);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const dateStr = `${year}-${month}-${day}`;

    return holidays.value.some(h => h.date === dateStr);
  };

  const isWeekend = (dateObj) => {
    const d = new Date(dateObj);
    const day = d.getDay();
    return day === 0 || day === 6; // 0 = Sunday, 6 = Saturday
  };

  const isDisabledDate = (dateObj) => {
    return isWeekend(dateObj) || isHoliday(dateObj);
  };

  const getWorkingDaysDiff = (startDate, endDate) => {
    let start = new Date(startDate);
    let end = new Date(endDate);
    
    // Normalize times to midnight to avoid partial day issues
    start.setHours(0, 0, 0, 0);
    end.setHours(0, 0, 0, 0);
    
    // Ensure start is before end. If not, swap or return negative
    let isNegative = false;
    if (start > end) {
      isNegative = true;
      let temp = start;
      start = end;
      end = temp;
    }

    let workingDaysCount = 0;
    let current = new Date(start);
    
    while (current < end) {
      current.setDate(current.getDate() + 1);
      if (!isDisabledDate(current)) {
        workingDaysCount++;
      }
    }

    return isNegative ? -workingDaysCount : workingDaysCount;
  };

  const addWorkingDays = (startDate, daysToAdd) => {
    let current = new Date(startDate);
    current.setHours(0, 0, 0, 0);
    let daysAdded = 0;

    while (daysAdded < daysToAdd) {
      current.setDate(current.getDate() + 1);
      if (!isDisabledDate(current)) {
        daysAdded++;
      }
    }
    return current;
  };

  return {
    holidays,
    isFetching,
    fetchHolidays,
    isHoliday,
    isWeekend,
    isDisabledDate,
    getWorkingDaysDiff,
    addWorkingDays
  };
}
