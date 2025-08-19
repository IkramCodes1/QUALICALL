import { getMessage } from '@/utils/message';
import Swal from 'sweetalert2';

export function showError(message) {
    console.log(message)
  Swal.fire({
    icon: 'error',
    title: 'Erreur',
    text: getMessage(message),
    confirmButtonColor: '#9155FD', 
    confirmButtonText: `<span style="color: #fff">Ok</span>`
  })
}
