export default function StatusBadge({ status }) {
  const isPublished = status === 'published';
  return (
    <span className={`status-badge status-badge--${status}`}>
      <span className="status-badge__dot" aria-hidden="true" />
      {isPublished ? 'Published' : 'Draft'}
    </span>
  );
}
