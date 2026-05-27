/**
 * useAuth — centralized authentication composable.
 *
 * Reads the user stored in localStorage after login and exposes:
 *  - user          : the raw object from localStorage
 *  - isLoggedIn    : true if user.id is present
 *  - isAdmin       : Director (new DB enum) or admin (legacy remote)
 *  - isStaff       : Staff (new DB enum) or gad_staff (legacy remote)
 *  - isCollege     : TWG / Non-TWG (new DB enum) or college (legacy remote)
 *  - requireRole() : call from onMounted to guard a view
 */
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export function useAuth() {
  const router = useRouter();
  const user   = ref(JSON.parse(localStorage.getItem('user') || '{}'));

  const isLoggedIn = !!user.value.id;
  const role       = user.value.role;

  const isAdmin   = ['Director', 'admin'].includes(role);
  const isStaff   = ['Staff', 'gad_staff'].includes(role);
  const isCollege = ['TWG', 'Non-TWG', 'college'].includes(role);

  /**
   * Call this inside onMounted to protect a page.
   * @param {string[]} allowedRoles  Array of allowed role strings.
   *                                 Pass null / undefined to just require login.
   */
  function requireRole(allowedRoles) {
    if (!user.value.id) {
      router.push('/login');
      return;
    }
    if (allowedRoles && !allowedRoles.includes(role)) {
      router.push('/login');
    }
  }

  return { user, isLoggedIn, isAdmin, isStaff, isCollege, requireRole };
}
