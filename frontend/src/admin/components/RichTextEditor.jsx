import { useRef, useEffect } from 'react';
import {
  IconBold,
  IconAlignLeft,
  IconAlignCenter,
  IconAlignRight,
  IconAlignJustify
} from '../../components/Icons';
import './RichTextEditor.css';

export default function RichTextEditor({ value, onChange, label, placeholder }) {
  const editorRef = useRef(null);

  // Sync value to editor content if external change
  useEffect(() => {
    if (editorRef.current && editorRef.current.innerHTML !== value) {
      editorRef.current.innerHTML = value || '';
    }
  }, [value]);

  const exec = (command, val = null) => {
    document.execCommand(command, false, val);
    if (editorRef.current) {
      onChange(editorRef.current.innerHTML);
    }
  };

  const handleInput = () => {
    if (editorRef.current) {
      onChange(editorRef.current.innerHTML);
    }
  };

  /**
   * Fix Copy-Paste Glitch:
   * Intercepts paste, strips out inline font colors, font-sizes, and font-families,
   * preserving text paragraphs and formatting so it strictly uses the Rasa font & theme color.
   */
  const handlePaste = (e) => {
    e.preventDefault();
    const clipboardData = e.clipboardData;
    let pastedData = clipboardData.getData('text/html') || clipboardData.getData('text/plain');

    if (!pastedData) return;

    // If pasted HTML exists, clean out inline color, font-size, background, and font-family style declarations
    let cleaned = pastedData
      .replace(/style="[^"]*"/gi, (match) => {
        // Strip out color, font-size, font-family, background from inline styles
        return match
          .replace(/color\s*:\s*[^;"]+;?/gi, '')
          .replace(/font-size\s*:\s*[^;"]+;?/gi, '')
          .replace(/font-family\s*:\s*[^;"]+;?/gi, '')
          .replace(/background(-color)?\s*:\s*[^;"]+;?/gi, '');
      })
      .replace(/<font[^>]*>/gi, '')
      .replace(/<\/font>/gi, '');

    // If html failed or empty, fallback to plain text lines
    if (!cleaned || !clipboardData.getData('text/html')) {
      const text = clipboardData.getData('text/plain');
      const formattedLines = text
        .split('\n')
        .map(line => line.trim() ? `<p>${line.trim()}</p>` : '<br>')
        .join('');
      cleaned = formattedLines;
    }

    document.execCommand('insertHTML', false, cleaned);
    if (editorRef.current) {
      onChange(editorRef.current.innerHTML);
    }
  };

  return (
    <div className="rich-editor">
      {label && <label className="form-label">{label}</label>}

      {/* Editor Toolbar with Bold + Alignments */}
      <div className="rich-editor__toolbar">
        <span className="rich-editor__font-badge">Font: Rasa</span>

        <div className="rich-editor__divider" />

        {/* Bold */}
        <button
          type="button"
          className="btn btn-secondary btn-sm rich-editor__btn"
          onClick={() => exec('bold')}
          title="Bold (Rasa Bold)"
        >
          <IconBold size={16} />
          <span>Bold</span>
        </button>

        <div className="rich-editor__divider" />

        {/* Alignment Buttons */}
        <button
          type="button"
          className="btn btn-ghost btn-sm rich-editor__btn"
          onClick={() => exec('justifyLeft')}
          title="Align Left"
        >
          <IconAlignLeft size={16} />
        </button>
        <button
          type="button"
          className="btn btn-ghost btn-sm rich-editor__btn"
          onClick={() => exec('justifyCenter')}
          title="Align Center"
        >
          <IconAlignCenter size={16} />
        </button>
        <button
          type="button"
          className="btn btn-ghost btn-sm rich-editor__btn"
          onClick={() => exec('justifyRight')}
          title="Align Right"
        >
          <IconAlignRight size={16} />
        </button>
        <button
          type="button"
          className="btn btn-ghost btn-sm rich-editor__btn"
          onClick={() => exec('justifyFull')}
          title="Justify Text"
        >
          <IconAlignJustify size={16} />
        </button>
      </div>

      {/* Editable Container */}
      <div
        ref={editorRef}
        className="rich-editor__content font-rasa"
        contentEditable
        onInput={handleInput}
        onBlur={handleInput}
        onPaste={handlePaste}
        data-placeholder={placeholder || 'Write chapter content here…'}
      />
    </div>
  );
}
