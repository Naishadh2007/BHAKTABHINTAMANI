import { createContext, useContext, useState, useEffect } from 'react';

const ReadingContext = createContext(null);

// Exact padding for each font size (manually tuned)
const PADDING_MAP = {
  20: '2.5rem',
  22: '2.5rem',
  24: '2.0rem',
  26: '1.8rem',
  28: '1.5rem',
  30: '1.2rem',
};

export function ReadingProvider({ children }) {
  // ── Font size (persisted) ──
  const [fontSize, setFontSizeState] = useState(() => {
    const saved = parseInt(localStorage.getItem('bc-font-size'), 10);
    return saved && saved >= 20 && saved <= 30 ? saved : 20;
  });

  const setFontSize = (size) => {
    setFontSizeState(size);
    localStorage.setItem('bc-font-size', String(size));
  };

  // Apply font size + matching padding to CSS variables
  useEffect(() => {
    document.documentElement.style.setProperty('--reading-font-size', `${fontSize}px`);
    document.documentElement.style.setProperty('--reading-padding', PADDING_MAP[fontSize] || '2.0rem');
  }, [fontSize]);

  // ── Single Bookmark (only ONE chapter at a time, persisted) ──
  const [bookmark, setBookmarkState] = useState(() => {
    try {
      const saved = JSON.parse(localStorage.getItem('bc-bookmark'));
      return saved && saved.chapterId ? saved : null;
    } catch {
      return null;
    }
  });

  // Set bookmark — replaces any existing bookmark
  const setBookmark = (chapterId, title, order) => {
    const bm = { chapterId, title, order };
    setBookmarkState(bm);
    localStorage.setItem('bc-bookmark', JSON.stringify(bm));
  };

  // Remove bookmark
  const removeBookmark = () => {
    setBookmarkState(null);
    localStorage.removeItem('bc-bookmark');
  };

  // Toggle: if same chapter → remove, otherwise → set new
  const toggleBookmark = (chapterId, title, order) => {
    if (bookmark && bookmark.chapterId === chapterId) {
      removeBookmark();
    } else {
      setBookmark(chapterId, title, order);
    }
  };

  const isBookmarked = (chapterId) => bookmark?.chapterId === chapterId;

  // ── Current chapter ID (set by ChapterPage) ──
  const [currentChapterId, setCurrentChapterId] = useState(null);

  return (
    <ReadingContext.Provider value={{
      fontSize, setFontSize,
      bookmark, toggleBookmark, isBookmarked,
      currentChapterId, setCurrentChapterId,
    }}>
      {children}
    </ReadingContext.Provider>
  );
}

export function useReading() {
  const ctx = useContext(ReadingContext);
  if (!ctx) throw new Error('useReading must be used within ReadingProvider');
  return ctx;
}

