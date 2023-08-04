import moment from 'moment';

export default function formatDateToLetters(dateString) {
  // Analizar la fecha original con moment
  const date = moment(dateString);

  // Obtener el formato deseado ("D MMM. YYYY" para "23 Jul. 2023")
  const formattedDate = date.format("D MMM. YYYY");

  return formattedDate;
}