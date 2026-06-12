/**
 * Format nomor telepon menjadi XXXX-XXXX-XXXX
 * @param {string} number Nomor telepon
 * @returns {string} Nomor terformat
 */
export function formatPhone(number) {
  if (!number) return ''
  return number.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3')
}

/**
 * Buat link WhatsApp dengan pesan default
 * @param {string} number Nomor telepon
 * @param {string} message Pesan default
 * @returns {string} Link WhatsApp
 */
export function createWhatsAppLink(number, name, subject = "", message = '') {
  if (!number) return ''
  const phone = number.replace(/^0/, '62')
  const encodedMessage = encodeURIComponent(`[Pesan dari website] Syalom pak Pdt. Jayanta Bangun, saya ${name} ingin menghubungi Anda.\n\Perihal: ${subject}\n\nPesan: ${message}`);
  return `https://wa.me/${phone}?text=${encodedMessage}`
}

/**
 * Buat link email dengan subject dan body default
 * @param {string} email Email
 * @param {string} subject Subject default
 * @param {string} body Isi pesan default
 * @returns {string} Link MailTo
 */
export function createMailLink(email = '', subject = '', body = '') {
  if (!email) return ''
  const encodedSubject = encodeURIComponent(subject)
  const encodedBody = encodeURIComponent(body)
  return `mailto:${email}?subject=Pesan dari Website: ${encodedSubject}&body=${encodedBody}`
}