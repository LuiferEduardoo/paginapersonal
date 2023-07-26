export default function formatTimeToMinRead(timeString) {
  // Dividir la cadena en horas, minutos y segundos
  const [hours, minutes, seconds] = timeString.split(":");

  // Convertir los valores a números enteros
  const totalMinutes = parseInt(hours) * 60 + parseInt(minutes);

  // Formatear el resultado como "X MIN READ"
  const formattedTime = `${totalMinutes} MIN READ`;

  return formattedTime;
}
