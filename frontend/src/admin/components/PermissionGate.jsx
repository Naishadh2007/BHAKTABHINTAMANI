import { useAuth } from '../../context/AuthContext';

/**
 * Renders children only if the current admin has the specified permission.
 * Super admin always sees everything.
 * Usage: <PermissionGate perm="manage_users"><UsersLink /></PermissionGate>
 */
export default function PermissionGate({ perm, fallback = null, children }) {
  const { hasPermission } = useAuth();
  return hasPermission(perm) ? children : fallback;
}
