/**
 * PermissionToggle — a labeled toggle switch for a single permission flag.
 * Usage:
 *   <PermissionToggle
 *     id="perm-manage_chapters"
 *     label="Manage Chapters"
 *     checked={perms.manage_chapters}
 *     onChange={val => setPerms(p => ({ ...p, manage_chapters: val }))}
 *   />
 */
export default function PermissionToggle({ id, label, checked, onChange }) {
  return (
    <div className="perm-toggle">
      <label htmlFor={id} className="perm-toggle__label">{label}</label>
      <label className="toggle-switch">
        <input
          id={id}
          type="checkbox"
          checked={checked}
          onChange={e => onChange(e.target.checked)}
        />
        <span className="toggle-track" />
      </label>
    </div>
  );
}
