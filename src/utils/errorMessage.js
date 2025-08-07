export function getErrorMessage(error) {
  if (error.response && error.response.data && error.response.data.message) {
    const msg = error.response.data.message
    if (typeof msg === 'string') {
      // Remplacer les points ou virgules par un retour à la ligne pour aérer le message
      return msg.replace(/([.;])\s*/g, '$1\n')
    } else if (typeof msg === 'object') {
      // Joindre chaque message sur une nouvelle ligne
      return Object.values(msg)
        .map(val => Array.isArray(val) ? val.join('\n') : val)
        .join('\n')
    } else {
      return JSON.stringify(msg)
    }
  }
  return 'Une erreur est survenue.'
}
