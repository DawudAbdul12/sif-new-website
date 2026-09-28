@csrf

@php
  $selectedType = old('type', $post->type ?: 'news');
  $selectedStatus = old('status', $post->status ?: 'draft');
  $selectedCategory = old('category_id', $post->category_id);
  $selectedCategoryModel = $categories->firstWhere('id', (int) $selectedCategory);
@endphp

@push('styles')
  <style>
    .post-editor {
      align-items: flex-start;
    }

    .post-hero-panel {
      overflow: hidden;
      border: 1px solid rgba(23, 71, 45, 0.12);
      background:
        linear-gradient(135deg, rgba(23, 71, 45, 0.06), rgba(216, 180, 73, 0.08)),
        #fff;
    }

    .post-editor-kicker {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--gb-green);
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0;
      text-transform: uppercase;
      margin-bottom: 14px;
    }

    .post-title-input {
      min-height: 54px;
      border: 0;
      border-bottom: 1px solid rgba(23, 71, 45, 0.14);
      border-radius: 0;
      background: transparent;
      color: var(--gb-green);
      font-size: clamp(1.45rem, 2.3vw, 2.35rem);
      font-weight: 800;
      line-height: 1.08;
      padding: 0 0 14px;
    }

    .post-title-input:focus {
      border-color: var(--gb-gold);
      box-shadow: none;
    }

    .post-field-shell {
      position: relative;
      margin-bottom: 18px;
    }

    .post-field-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      color: var(--gb-muted);
      font-size: 0.76rem;
      font-weight: 700;
      margin-top: 8px;
    }

    .post-rich-textarea {
      min-height: 320px;
      border-color: rgba(23, 71, 45, 0.16);
      font-size: 1rem;
      line-height: 1.7;
    }

    .wysiwyg-shell {
      overflow: hidden;
      border: 1px solid rgba(23, 71, 45, 0.14);
      border-radius: 8px;
      background: #fff;
    }

    .wysiwyg-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 7px;
      padding: 10px;
      border-bottom: 1px solid rgba(23, 71, 45, 0.1);
      background: #f8faf7;
    }

    .wysiwyg-group {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding-right: 7px;
      border-right: 1px solid rgba(23, 71, 45, 0.12);
    }

    .wysiwyg-group:last-child {
      border-right: 0;
      padding-right: 0;
    }

    .wysiwyg-btn,
    .wysiwyg-select,
    .wysiwyg-color {
      min-width: 36px;
      height: 36px;
      border: 1px solid #d7ded9;
      border-radius: 6px;
      background: #fff;
      color: var(--gb-green);
      font-weight: 800;
    }

    .wysiwyg-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0 9px;
    }

    .wysiwyg-btn:hover,
    .wysiwyg-btn.active {
      border-color: var(--gb-green);
      background: var(--gb-green);
      color: #fff;
    }

    .wysiwyg-select {
      min-width: 132px;
      padding: 0 10px;
      font-size: 0.82rem;
    }

    .wysiwyg-color {
      width: 38px;
      padding: 5px;
    }

    .wysiwyg-editor {
      min-height: 900px;
      padding: 22px;
      color: var(--gb-ink);
      font-size: 1rem;
      line-height: 1.75;
      outline: none;
    }

    .wysiwyg-editor:empty::before {
      content: attr(data-placeholder);
      color: #8a978f;
      font-weight: 600;
    }

    .wysiwyg-editor h2,
    .wysiwyg-editor h3,
    .wysiwyg-editor h4 {
      color: var(--gb-green);
      font-weight: 800;
      margin: 1.15em 0 0.45em;
    }

    .wysiwyg-editor blockquote {
      margin: 18px 0;
      padding: 14px 18px;
      border-left: 4px solid var(--gb-gold);
      background: #fff9e8;
      color: var(--gb-green);
      font-weight: 700;
    }

    .wysiwyg-editor img,
    .wysiwyg-editor iframe {
      max-width: 100%;
      border-radius: 8px;
    }

    .wysiwyg-editor figure.post-media {
      position: relative;
      display: block;
      max-width: 100%;
      margin: 18px 0;
      padding: 10px;
      border: 1px solid transparent;
      border-radius: 8px;
    }

    .wysiwyg-editor figure.post-media.is-selected {
      border-color: var(--gb-gold);
      background: #fffdf5;
      box-shadow: 0 14px 34px rgba(23, 71, 45, 0.12);
    }

    .wysiwyg-editor figure.post-media.align-center {
      margin-left: auto;
      margin-right: auto;
      text-align: center;
    }

    .wysiwyg-editor figure.post-media.align-right {
      margin-left: auto;
      text-align: right;
    }

    .wysiwyg-editor figure.post-media.align-left {
      margin-right: auto;
      text-align: left;
    }

    .wysiwyg-editor figure.post-media.align-full {
      width: 100% !important;
    }

    .wysiwyg-editor figure.post-media img {
      width: 100%;
      height: auto;
      display: block;
    }

    .wysiwyg-editor figure.post-media figcaption {
      color: var(--gb-muted);
      font-size: 0.84rem;
      font-weight: 700;
      margin-top: 8px;
    }

    .editor-media-controls {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 2;
      display: none;
      flex-wrap: wrap;
      gap: 5px;
      padding: 6px;
      border: 1px solid rgba(23, 71, 45, 0.14);
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.94);
      box-shadow: 0 10px 24px rgba(23, 71, 45, 0.16);
    }

    .wysiwyg-editor figure.post-media.is-selected .editor-media-controls {
      display: flex;
    }

    .editor-media-btn {
      width: 30px;
      height: 30px;
      border: 1px solid #d7ded9;
      border-radius: 6px;
      background: #fff;
      color: var(--gb-green);
      font-size: 0.82rem;
      font-weight: 800;
    }

    .editor-media-btn:hover {
      background: var(--gb-green);
      color: #fff;
      border-color: var(--gb-green);
    }

    .editor-media-size {
      width: 92px;
      accent-color: var(--gb-green);
    }

    .editor-media-handle {
      position: absolute;
      right: 4px;
      bottom: 4px;
      z-index: 2;
      display: none;
      width: 18px;
      height: 18px;
      border-right: 3px solid var(--gb-gold);
      border-bottom: 3px solid var(--gb-gold);
      cursor: nwse-resize;
    }

    .wysiwyg-editor figure.post-media.is-selected .editor-media-handle {
      display: block;
    }

    .wysiwyg-source {
      display: none;
      min-height: 900px;
      border: 0;
      border-radius: 0;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: 0.88rem;
      line-height: 1.65;
    }

    .wysiwyg-shell.source-mode .wysiwyg-editor {
      display: none;
    }

    .wysiwyg-shell.source-mode .wysiwyg-source {
      display: block;
    }

    .wysiwyg-shell.fullscreen {
      position: fixed;
      inset: 18px;
      z-index: 1050;
      display: flex;
      flex-direction: column;
      box-shadow: 0 30px 90px rgba(23, 71, 45, 0.34);
    }

    .wysiwyg-shell.fullscreen .wysiwyg-editor,
    .wysiwyg-shell.fullscreen .wysiwyg-source {
      flex: 1;
      min-height: 0;
    }

    .editor-dialog-backdrop {
      position: fixed;
      inset: 0;
      z-index: 1060;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 18px;
      background: rgba(12, 36, 24, 0.56);
      backdrop-filter: blur(8px);
    }

    .editor-dialog-backdrop.active {
      display: flex;
    }

    .editor-dialog {
      width: min(880px, 100%);
      max-height: min(92vh, 820px);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.24);
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 34px 90px rgba(12, 36, 24, 0.36);
    }

    .editor-dialog-header,
    .editor-dialog-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 18px 20px;
      background: #f8faf7;
      border-bottom: 1px solid rgba(23, 71, 45, 0.1);
    }

    .editor-dialog-footer {
      justify-content: flex-end;
      border-top: 1px solid rgba(23, 71, 45, 0.1);
      border-bottom: 0;
    }

    .editor-dialog-title {
      color: var(--gb-green);
      font-size: 1rem;
      font-weight: 800;
      margin: 0;
    }

    .editor-dialog-body {
      max-height: 64vh;
      overflow: auto;
      padding: 20px;
    }

    .editor-dialog-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }

    .editor-dialog-grid .span-2 {
      grid-column: 1 / -1;
    }

    .editor-dialog-note {
      border: 1px solid rgba(216, 180, 73, 0.32);
      border-radius: 8px;
      background: #fff9e8;
      color: #725600;
      padding: 12px 14px;
      font-size: 0.82rem;
      font-weight: 700;
    }

    .editor-upload-panel {
      border: 1px dashed rgba(23, 71, 45, 0.28);
      border-radius: 8px;
      background: #f8faf7;
      padding: 14px;
    }

    .editor-upload-status {
      color: var(--gb-muted);
      font-size: 0.8rem;
      font-weight: 700;
      margin-top: 8px;
    }

    .editor-upload-preview {
      display: none;
      overflow: hidden;
      margin-top: 12px;
      border: 1px solid rgba(23, 71, 45, 0.12);
      border-radius: 8px;
      background: #fff;
    }

    .editor-upload-preview.active {
      display: block;
    }

    .editor-upload-preview img {
      width: 100%;
      max-height: 220px;
      object-fit: cover;
      display: block;
    }

    .editor-library-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(112px, 1fr));
      gap: 10px;
      max-height: 270px;
      overflow: auto;
      padding: 4px;
    }

    .editor-library-item {
      overflow: hidden;
      border: 1px solid #d7ded9;
      border-radius: 8px;
      background: #fff;
      color: var(--gb-green);
      text-align: left;
      padding: 0;
    }

    .editor-library-item:hover,
    .editor-library-item.active {
      border-color: var(--gb-gold);
      box-shadow: 0 10px 24px rgba(23, 71, 45, 0.14);
    }

    .editor-library-item img {
      width: 100%;
      aspect-ratio: 4 / 3;
      object-fit: cover;
      display: block;
      background: #f8faf7;
    }

    .editor-library-item span {
      display: block;
      overflow: hidden;
      padding: 8px;
      font-size: 0.72rem;
      font-weight: 800;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    .editor-check-row {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
    }

    .editor-check-row label {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: var(--gb-green);
      font-size: 0.82rem;
      font-weight: 800;
    }

    .editor-dialog-close {
      width: 36px;
      height: 36px;
      border: 1px solid #d7ded9;
      border-radius: 6px;
      background: #fff;
      color: var(--gb-green);
    }

    @media (max-width: 720px) {
      .editor-dialog-grid {
        grid-template-columns: 1fr;
      }

      .editor-dialog-grid .span-2 {
        grid-column: auto;
      }
    }

    .post-sidebar {
      position: sticky;
      top: 24px;
    }

    .post-panel-heading {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 18px;
    }

    .post-panel-title {
      display: flex;
      align-items: center;
      gap: 9px;
      color: var(--gb-green);
      font-weight: 800;
      margin: 0;
    }

    .post-panel-title i {
      color: var(--gb-gold);
    }

    .post-segment-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
    }

    .post-segment {
      position: relative;
      display: block;
      margin: 0;
    }

    .post-segment input {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .post-segment span {
      display: flex;
      min-height: 48px;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border: 1px solid #d7ded9;
      border-radius: 6px;
      background: #fff;
      color: var(--gb-green);
      font-size: 0.86rem;
      font-weight: 800;
      cursor: pointer;
    }

    .post-segment input:checked + span {
      border-color: var(--gb-green);
      background: var(--gb-green);
      color: #fff;
      box-shadow: 0 12px 28px rgba(23, 71, 45, 0.18);
    }

    .post-status-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
    }

    .post-status-option input:checked + span {
      background: #fff7df;
      border-color: var(--gb-gold);
      color: #946c00;
      box-shadow: none;
    }

    .post-status-option input[value="published"]:checked + span {
      background: #edf5ef;
      border-color: var(--gb-green);
      color: var(--gb-green);
    }

    .post-category-preview {
      display: flex;
      align-items: center;
      gap: 10px;
      min-height: 46px;
      padding: 11px 12px;
      border: 1px solid rgba(23, 71, 45, 0.12);
      border-radius: 6px;
      background: #f8faf7;
      color: var(--gb-muted);
      font-size: 0.82rem;
      font-weight: 700;
    }

    .post-category-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: {{ $selectedCategoryModel?->color ?? '#d8b449' }};
      flex: 0 0 auto;
    }

    .post-image-preview {
      display: grid;
      min-height: 170px;
      place-items: center;
      overflow: hidden;
      border: 1px dashed rgba(23, 71, 45, 0.26);
      border-radius: 8px;
      background: #f8faf7;
      color: var(--gb-muted);
      text-align: center;
      font-weight: 700;
    }

    .post-image-preview img {
      width: 100%;
      height: 210px;
      object-fit: cover;
      display: block;
    }

    .feature-image-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
      margin-bottom: 14px;
    }

    .feature-image-actions .admin-btn-secondary,
    .feature-image-actions .admin-danger {
      min-height: 42px;
      padding: 9px 10px;
      font-size: 0.78rem;
    }

    .feature-image-status {
      color: var(--gb-muted);
      font-size: 0.78rem;
      font-weight: 700;
      margin: -4px 0 12px;
    }

    .post-save-bar {
      display: flex;
      gap: 10px;
      margin-top: 16px;
    }

    .post-save-bar .admin-btn,
    .post-save-bar .admin-btn-secondary {
      flex: 1;
    }

    @media (max-width: 991px) {
      .post-sidebar {
        position: static;
      }
    }
  </style>
@endpush

<div class="row g-3 post-editor">
  <div class="col-xl-8 col-lg-7">
    <div class="admin-panel post-hero-panel mb-3">
      <div class="post-editor-kicker"><i class="bi bi-pencil-square"></i> Editorial Composer</div>

      <div class="post-field-shell">
        <label class="admin-label visually-hidden" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $post->title) }}" class="admin-control post-title-input" placeholder="Post title" required data-count-target="title-count">
        <div class="post-field-meta">
          <span>Headline</span>
          <span><span id="title-count">0</span> characters</span>
        </div>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="post-field-shell">
        <label class="admin-label" for="excerpt">Brief Description</label>
        <textarea id="excerpt" name="excerpt" class="admin-textarea" style="min-height: 92px" placeholder="Concise summary for cards, listings, and search results." data-count-target="excerpt-count">{{ old('excerpt', $post->excerpt) }}</textarea>
        <div class="post-field-meta">
          <span>Summary</span>
          <span><span id="excerpt-count">0</span> / 1000</span>
        </div>
      </div>
    </div>

    <div class="admin-panel mb-3">
      <div class="post-panel-heading">
        <h2 class="h6 post-panel-title"><i class="bi bi-body-text"></i> Content Body</h2>
        <span class="admin-badge">{{ ucfirst($selectedType) }}</span>
      </div>

      <div class="post-field-shell mb-0">
        <label class="admin-label visually-hidden" for="body">Body</label>
        <textarea id="body" name="body" class="d-none" data-count-target="body-count">{{ old('body', $post->body) }}</textarea>

        <div class="wysiwyg-shell" id="body-editor-shell">
          <div class="wysiwyg-toolbar" role="toolbar" aria-label="Content editor toolbar">
            <div class="wysiwyg-group">
              <select class="wysiwyg-select" data-format-block aria-label="Text style">
                <option value="p">Paragraph</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
                <option value="blockquote">Quote</option>
                <option value="pre">Code Block</option>
              </select>
            </div>
            <div class="wysiwyg-group">
              <button type="button" class="wysiwyg-btn" data-command="bold" title="Bold"><i class="bi bi-type-bold"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="italic" title="Italic"><i class="bi bi-type-italic"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="underline" title="Underline"><i class="bi bi-type-underline"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="strikeThrough" title="Strikethrough"><i class="bi bi-type-strikethrough"></i></button>
            </div>
            <div class="wysiwyg-group">
              <button type="button" class="wysiwyg-btn" data-command="insertUnorderedList" title="Bullet List"><i class="bi bi-list-ul"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="insertOrderedList" title="Numbered List"><i class="bi bi-list-ol"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="outdent" title="Outdent"><i class="bi bi-text-indent-left"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="indent" title="Indent"><i class="bi bi-text-indent-right"></i></button>
            </div>
            <div class="wysiwyg-group">
              <button type="button" class="wysiwyg-btn" data-command="justifyLeft" title="Align Left"><i class="bi bi-text-left"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="justifyCenter" title="Align Center"><i class="bi bi-text-center"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="justifyRight" title="Align Right"><i class="bi bi-text-right"></i></button>
            </div>
            <div class="wysiwyg-group">
              <input type="color" class="wysiwyg-color" value="#172d22" data-color-command="foreColor" title="Text Color">
              <input type="color" class="wysiwyg-color" value="#fff7df" data-color-command="hiliteColor" title="Highlight Color">
            </div>
            <div class="wysiwyg-group">
              <button type="button" class="wysiwyg-btn" data-action="link" title="Insert Link"><i class="bi bi-link-45deg"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="unlink" title="Remove Link"><i class="bi bi-link-45deg"></i><i class="bi bi-x"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="image" title="Insert Image"><i class="bi bi-image"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="video" title="Embed Video"><i class="bi bi-play-btn"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="table" title="Insert Table"><i class="bi bi-table"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="insertHorizontalRule" title="Divider"><i class="bi bi-hr"></i></button>
            </div>
            <div class="wysiwyg-group">
              <button type="button" class="wysiwyg-btn" data-command="undo" title="Undo"><i class="bi bi-arrow-counterclockwise"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="redo" title="Redo"><i class="bi bi-arrow-clockwise"></i></button>
              <button type="button" class="wysiwyg-btn" data-command="removeFormat" title="Clear Formatting"><i class="bi bi-eraser"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="source" title="HTML Source"><i class="bi bi-code-slash"></i></button>
              <button type="button" class="wysiwyg-btn" data-action="fullscreen" title="Fullscreen"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
          </div>

          <div id="body-editor" class="wysiwyg-editor" contenteditable="true" data-placeholder="Write the full post content. Use headings, links, media, lists, quotes, and tables.">{!! old('body', $post->body) !!}</div>
          <textarea id="body-source" class="admin-textarea wysiwyg-source" spellcheck="false"></textarea>
        </div>

        <div class="post-field-meta">
          <span><span id="body-word-count">0</span> words</span>
          <span><span id="body-count">0</span> characters</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-5">
    <div class="post-sidebar">
      <div class="admin-panel mb-3">
        <div class="post-panel-heading">
          <h2 class="h6 post-panel-title"><i class="bi bi-send-check"></i> Publish</h2>
          @if($post->exists)
            <span class="small text-muted">Updated {{ $post->updated_at->diffForHumans() }}</span>
          @endif
        </div>

        <div class="mb-3">
          <label class="admin-label d-block">Type</label>
          <div class="post-segment-grid">
            @foreach(['news' => 'News', 'article' => 'Article'] as $type => $label)
              <label class="post-segment">
                <input type="radio" name="type" value="{{ $type }}" @checked($selectedType === $type)>
                <span><i class="bi {{ $type === 'news' ? 'bi-newspaper' : 'bi-journal-richtext' }}"></i>{{ $label }}</span>
              </label>
            @endforeach
          </div>
          @error('type') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="admin-label d-block">Status</label>
          <div class="post-status-grid">
            @foreach(['draft' => 'Draft', 'review' => 'Review', 'published' => 'Published', 'archived' => 'Archived'] as $status => $label)
              <label class="post-segment post-status-option">
                <input type="radio" name="status" value="{{ $status }}" @checked($selectedStatus === $status)>
                <span>{{ $label }}</span>
              </label>
            @endforeach
          </div>
          @error('status') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="admin-label" for="published_at">Publish Date</label>
          <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
        </div>

        <div class="post-save-bar">
          <a href="{{ route('admin.posts.index') }}" class="admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
          <button type="submit" class="admin-btn"><i class="bi bi-check2"></i> Save</button>
        </div>
      </div>

      <div class="admin-panel mb-3">
        <div class="post-panel-heading">
          <h2 class="h6 post-panel-title"><i class="bi bi-tags"></i> Taxonomy</h2>
          <a href="{{ route('admin.categories.create') }}" class="small fw-bold text-success text-decoration-none">New</a>
        </div>

        <div class="mb-3">
          <label class="admin-label" for="category_id">Category</label>
          <select id="category_id" name="category_id" class="admin-select">
            <option value="">Uncategorized</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" data-color="{{ $category->color }}" data-label="{{ $category->typeLabel() }}" @selected((string) $selectedCategory === (string) $category->id)>
                {{ $category->name }} · {{ $category->typeLabel() }}
              </option>
            @endforeach
          </select>
          @error('category_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="post-category-preview" id="category-preview">
          <span class="post-category-dot" id="category-dot"></span>
          <span id="category-copy">{{ $selectedCategoryModel ? $selectedCategoryModel->name.' · '.$selectedCategoryModel->typeLabel() : 'Uncategorized' }}</span>
        </div>
      </div>

      <div class="admin-panel mb-3">
        <div class="post-panel-heading">
          <h2 class="h6 post-panel-title"><i class="bi bi-image"></i> Feature Image</h2>
        </div>

        <div class="post-image-preview mb-3" id="featured-preview">
          @if(old('featured_image', $post->featured_image))
            <img src="{{ old('featured_image', $post->featured_image) }}" alt="">
          @else
            <span><i class="bi bi-image d-block h3 mb-2"></i>No image selected</span>
          @endif
        </div>

        <input id="featured_image_file" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
        <div class="feature-image-actions">
          <button type="button" class="admin-btn-secondary" id="featured-browse"><i class="bi bi-upload"></i> Browse</button>
          <button type="button" class="admin-btn-secondary" id="featured-library"><i class="bi bi-images"></i> Library</button>
          <button type="button" class="admin-btn-secondary" id="featured-refresh"><i class="bi bi-arrow-clockwise"></i> Preview</button>
          <button type="button" class="admin-danger" id="featured-clear"><i class="bi bi-trash"></i> Clear</button>
        </div>
        <div class="feature-image-status" id="featured-status">Use a strong landscape image for listing cards and previews.</div>

        <label class="admin-label" for="featured_image">Image URL</label>
        <input id="featured_image" name="featured_image" value="{{ old('featured_image', $post->featured_image) }}" class="admin-control" placeholder="https://">
      </div>

      <div class="admin-panel">
        <div class="post-panel-heading">
          <h2 class="h6 post-panel-title"><i class="bi bi-search"></i> SEO</h2>
          <button type="button" class="admin-btn-secondary" id="seo-generate" style="padding: 7px 10px; font-size: 0.76rem"><i class="bi bi-magic"></i> Generate</button>
        </div>

        <div class="mb-3">
          <label class="admin-label" for="slug">Slug</label>
          <input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="admin-control" placeholder="auto-generated">
          @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="admin-label" for="seo_title">SEO Title</label>
          <input id="seo_title" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" class="admin-control" data-count-target="seo-title-count">
          <div class="post-field-meta">
            <span>Search title · auto-generates if empty</span>
            <span><span id="seo-title-count">0</span> characters</span>
          </div>
        </div>

        <div class="mb-0">
          <label class="admin-label" for="seo_description">SEO Description</label>
          <textarea id="seo_description" name="seo_description" class="admin-textarea" style="min-height: 118px" data-count-target="seo-description-count">{{ old('seo_description', $post->seo_description) }}</textarea>
          <div class="post-field-meta">
            <span>Search summary · auto-generates if empty</span>
            <span><span id="seo-description-count">0</span> characters</span>
          </div>
        </div>
      </div>

      @if($post->exists)
        @include('admin.activity-logs._record-panel', [
          'recordActivityLogs' => $recordActivityLogs ?? collect(),
          'recordActivityType' => $post::class,
          'recordActivityId' => $post->id,
        ])
      @endif
    </div>
  </div>
</div>

<div class="editor-dialog-backdrop" id="link-dialog" aria-hidden="true">
  <div class="editor-dialog" role="dialog" aria-modal="true" aria-labelledby="link-dialog-title">
    <div class="editor-dialog-header">
      <h3 class="editor-dialog-title" id="link-dialog-title"><i class="bi bi-link-45deg"></i> Link Settings</h3>
      <button type="button" class="editor-dialog-close" data-dialog-close><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="editor-dialog-body">
      <div class="editor-dialog-grid">
        <div class="span-2">
          <label class="admin-label" for="link-url">URL</label>
          <input id="link-url" class="admin-control" placeholder="https://example.com">
        </div>
        <div>
          <label class="admin-label" for="link-text">Display Text</label>
          <input id="link-text" class="admin-control" placeholder="Selected text or label">
        </div>
        <div>
          <label class="admin-label" for="link-title">Title Attribute</label>
          <input id="link-title" class="admin-control" placeholder="Optional tooltip">
        </div>
        <div>
          <label class="admin-label" for="link-target">Open Behavior</label>
          <select id="link-target" class="admin-select">
            <option value="">Same tab</option>
            <option value="_blank">New tab</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="link-rel">Rel Attribute</label>
          <input id="link-rel" class="admin-control" placeholder="noopener noreferrer">
        </div>
        <div>
          <label class="admin-label" for="link-aria">ARIA Label</label>
          <input id="link-aria" class="admin-control" placeholder="Accessible link label">
        </div>
        <div>
          <label class="admin-label" for="link-class">CSS Class</label>
          <input id="link-class" class="admin-control" placeholder="btn-link, external-link">
        </div>
        <div class="span-2 editor-check-row">
          <label><input id="link-sponsored" type="checkbox"> Sponsored</label>
          <label><input id="link-nofollow" type="checkbox"> No follow</label>
          <label><input id="link-download" type="checkbox"> Download</label>
        </div>
      </div>
    </div>
    <div class="editor-dialog-footer">
      <button type="button" class="admin-danger me-auto" id="link-remove"><i class="bi bi-link-45deg"></i> Remove Link</button>
      <button type="button" class="admin-btn-secondary" data-dialog-close>Cancel</button>
      <button type="button" class="admin-btn" id="link-apply"><i class="bi bi-check2"></i> Apply Link</button>
    </div>
  </div>
</div>

<div class="editor-dialog-backdrop" id="image-dialog" aria-hidden="true">
  <div class="editor-dialog" role="dialog" aria-modal="true" aria-labelledby="image-dialog-title">
    <div class="editor-dialog-header">
      <h3 class="editor-dialog-title" id="image-dialog-title"><i class="bi bi-image"></i> Image Settings</h3>
      <button type="button" class="editor-dialog-close" data-dialog-close><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="editor-dialog-body">
      <div class="editor-dialog-grid">
        <div class="span-2 editor-upload-panel">
          <label class="admin-label" for="image-file">Browse Local Image</label>
          <input id="image-file" type="file" class="admin-control" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
          <div class="editor-upload-preview" id="image-upload-preview"></div>
          <div class="editor-upload-status" id="image-upload-status">Choose an image and it will upload automatically.</div>
        </div>
        <div class="span-2">
          <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <label class="admin-label mb-0" for="image-library-search">Media Library</label>
            <input id="image-library-search" class="admin-control" style="max-width: 260px; padding: 9px 11px" placeholder="Search media">
          </div>
          <div class="editor-library-grid" id="image-library-grid"></div>
          <div class="editor-upload-status" id="image-library-status">Loading latest 20 images...</div>
          <button type="button" class="admin-btn-secondary mt-2 w-100" id="image-library-more"><i class="bi bi-plus-lg"></i> Load More</button>
        </div>
        <div class="span-2">
          <label class="admin-label" for="image-url">Image URL</label>
          <input id="image-url" class="admin-control" placeholder="https://example.com/image.jpg">
        </div>
        <div>
          <label class="admin-label" for="image-alt">Alt Text</label>
          <input id="image-alt" class="admin-control" placeholder="Describe the image">
        </div>
        <div>
          <label class="admin-label" for="image-title">Title Attribute</label>
          <input id="image-title" class="admin-control" placeholder="Optional tooltip">
        </div>
        <div>
          <label class="admin-label" for="image-caption">Caption</label>
          <input id="image-caption" class="admin-control" placeholder="Optional caption">
        </div>
        <div>
          <label class="admin-label" for="image-link">Click-through URL</label>
          <input id="image-link" class="admin-control" placeholder="Optional link">
        </div>
        <div>
          <label class="admin-label" for="image-width">Width</label>
          <input id="image-width" class="admin-control" placeholder="auto, 640, 100%">
        </div>
        <div>
          <label class="admin-label" for="image-height">Height</label>
          <input id="image-height" class="admin-control" placeholder="auto or pixels">
        </div>
        <div>
          <label class="admin-label" for="image-align">Alignment</label>
          <select id="image-align" class="admin-select">
            <option value="">Default</option>
            <option value="left">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
            <option value="full">Full width</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="image-loading">Loading</label>
          <select id="image-loading" class="admin-select">
            <option value="lazy">Lazy</option>
            <option value="eager">Eager</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="image-class">CSS Class</label>
          <input id="image-class" class="admin-control" placeholder="rounded, article-image">
        </div>
        <div>
          <label class="admin-label" for="image-credit">Credit</label>
          <input id="image-credit" class="admin-control" placeholder="Photo credit">
        </div>
        <div class="span-2 editor-dialog-note">Use clear alt text for accessibility. Captions and credits are wrapped in a semantic figure.</div>
      </div>
    </div>
    <div class="editor-dialog-footer">
      <button type="button" class="admin-btn-secondary" data-dialog-close>Cancel</button>
      <button type="button" class="admin-btn" id="image-apply"><i class="bi bi-check2"></i> Insert Image</button>
    </div>
  </div>
</div>

<div class="editor-dialog-backdrop" id="table-dialog" aria-hidden="true">
  <div class="editor-dialog" role="dialog" aria-modal="true" aria-labelledby="table-dialog-title">
    <div class="editor-dialog-header">
      <h3 class="editor-dialog-title" id="table-dialog-title"><i class="bi bi-table"></i> Table Builder</h3>
      <button type="button" class="editor-dialog-close" data-dialog-close><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="editor-dialog-body">
      <div class="editor-dialog-grid">
        <div>
          <label class="admin-label" for="table-rows">Rows</label>
          <input id="table-rows" type="number" min="1" max="24" value="3" class="admin-control">
        </div>
        <div>
          <label class="admin-label" for="table-columns">Columns</label>
          <input id="table-columns" type="number" min="1" max="12" value="3" class="admin-control">
        </div>
        <div>
          <label class="admin-label" for="table-caption">Caption</label>
          <input id="table-caption" class="admin-control" placeholder="Optional table caption">
        </div>
        <div>
          <label class="admin-label" for="table-summary">ARIA Label</label>
          <input id="table-summary" class="admin-control" placeholder="Accessible table label">
        </div>
        <div>
          <label class="admin-label" for="table-style">Style</label>
          <select id="table-style" class="admin-select">
            <option value="clean">Clean</option>
            <option value="striped">Striped</option>
            <option value="bordered">Bordered</option>
            <option value="compact">Compact</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="table-align">Alignment</label>
          <select id="table-align" class="admin-select">
            <option value="">Default</option>
            <option value="center">Center</option>
            <option value="full">Full width</option>
          </select>
        </div>
        <div class="span-2 editor-check-row">
          <label><input id="table-header-row" type="checkbox" checked> Header row</label>
          <label><input id="table-header-column" type="checkbox"> Header column</label>
          <label><input id="table-responsive" type="checkbox" checked> Responsive wrapper</label>
        </div>
        <div class="span-2 editor-dialog-note">The table inserts accessible HTML with optional caption, header cells, and responsive wrapping for mobile screens.</div>
      </div>
    </div>
    <div class="editor-dialog-footer">
      <button type="button" class="admin-btn-secondary" data-dialog-close>Cancel</button>
      <button type="button" class="admin-btn" id="table-apply"><i class="bi bi-check2"></i> Insert Table</button>
    </div>
  </div>
</div>

<div class="editor-dialog-backdrop" id="video-dialog" aria-hidden="true">
  <div class="editor-dialog" role="dialog" aria-modal="true" aria-labelledby="video-dialog-title">
    <div class="editor-dialog-header">
      <h3 class="editor-dialog-title" id="video-dialog-title"><i class="bi bi-play-btn"></i> Video Embed Settings</h3>
      <button type="button" class="editor-dialog-close" data-dialog-close><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="editor-dialog-body">
      <div class="editor-dialog-grid">
        <div class="span-2">
          <label class="admin-label" for="video-url">Video URL or Embed URL</label>
          <input id="video-url" class="admin-control" placeholder="YouTube, Vimeo, or iframe src">
        </div>
        <div>
          <label class="admin-label" for="video-title">Title</label>
          <input id="video-title" class="admin-control" placeholder="Accessible video title">
        </div>
        <div>
          <label class="admin-label" for="video-caption">Caption</label>
          <input id="video-caption" class="admin-control" placeholder="Optional caption">
        </div>
        <div>
          <label class="admin-label" for="video-ratio">Aspect Ratio</label>
          <select id="video-ratio" class="admin-select">
            <option value="56.25%">16:9</option>
            <option value="75%">4:3</option>
            <option value="100%">1:1</option>
            <option value="42.85%">21:9</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="video-loading">Loading</label>
          <select id="video-loading" class="admin-select">
            <option value="lazy">Lazy</option>
            <option value="eager">Eager</option>
          </select>
        </div>
        <div>
          <label class="admin-label" for="video-width">Max Width</label>
          <input id="video-width" class="admin-control" placeholder="100%, 720px">
        </div>
        <div>
          <label class="admin-label" for="video-class">CSS Class</label>
          <input id="video-class" class="admin-control" placeholder="video-embed">
        </div>
        <div class="span-2 editor-check-row">
          <label><input id="video-autoplay" type="checkbox"> Autoplay</label>
          <label><input id="video-muted" type="checkbox"> Muted</label>
          <label><input id="video-controls" type="checkbox" checked> Controls</label>
          <label><input id="video-privacy" type="checkbox" checked> YouTube privacy mode</label>
        </div>
      </div>
    </div>
    <div class="editor-dialog-footer">
      <button type="button" class="admin-btn-secondary" data-dialog-close>Cancel</button>
      <button type="button" class="admin-btn" id="video-apply"><i class="bi bi-check2"></i> Insert Video</button>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    const syncCounter = (field) => {
      const target = document.getElementById(field.dataset.countTarget);
      if (! target) {
        return;
      }

      const updateCount = () => {
        target.textContent = field.value.length;
      };

      updateCount();
      field.addEventListener('input', updateCount);
    };

    document.querySelectorAll('[data-count-target]').forEach((field) => {
      syncCounter(field);
    });

    const categorySelect = document.getElementById('category_id');
    const categoryDot = document.getElementById('category-dot');
    const categoryCopy = document.getElementById('category-copy');

    categorySelect?.addEventListener('change', () => {
      const option = categorySelect.selectedOptions[0];
      categoryDot.style.background = option.dataset.color || '#d8b449';
      categoryCopy.textContent = option.value ? `${option.textContent.trim()}` : 'Uncategorized';
    });

    const featuredImage = document.getElementById('featured_image');
    const featuredPreview = document.getElementById('featured-preview');
    const titleField = document.getElementById('title');
    const excerptField = document.getElementById('excerpt');
    const seoTitleField = document.getElementById('seo_title');
    const seoDescriptionField = document.getElementById('seo_description');

    const bodyTextarea = document.getElementById('body');
    const editorShell = document.getElementById('body-editor-shell');
    const editor = document.getElementById('body-editor');
    const source = document.getElementById('body-source');
    const wordCounter = document.getElementById('body-word-count');
    const form = bodyTextarea?.closest('form');
    const editorImageUploadUrl = @json(route('admin.editor.images.store'));
    const editorMediaUrl = @json(route('admin.editor.media.index'));
    const csrfToken = @json(csrf_token());
    let savedRange = null;
    let imageLibraryPage = 1;
    let imageLibrarySearch = '';
    let imageLibraryHasMore = true;
    let imageLibraryLoaded = false;
    let imageLibraryTimer = null;
    let imageDialogMode = 'body';

    const escapeHtml = (value = '') => value
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const attr = (name, value) => value ? ` ${name}="${escapeHtml(value)}"` : '';
    const checked = (id) => document.getElementById(id)?.checked;
    const valueOf = (id) => document.getElementById(id)?.value.trim() || '';

    const compactText = (value) => value.replace(/\s+/g, ' ').trim();
    const limitText = (value, length) => compactText(value).slice(0, length);

    const generateSeoFields = ({ force = true } = {}) => {
      const title = limitText(titleField?.value || '', 60);
      const bodyText = editor?.innerText || '';
      const description = limitText(excerptField?.value || bodyText, 160);

      if (seoTitleField && (force || ! seoTitleField.value.trim())) {
        seoTitleField.value = title;
        seoTitleField.dispatchEvent(new Event('input', { bubbles: true }));
      }

      if (seoDescriptionField && (force || ! seoDescriptionField.value.trim())) {
        seoDescriptionField.value = description;
        seoDescriptionField.dispatchEvent(new Event('input', { bubbles: true }));
      }
    };

    const setFeaturedImage = (url, status = 'Feature image ready.') => {
      if (! featuredImage || ! featuredPreview) {
        return;
      }

      featuredImage.value = url;
      featuredPreview.innerHTML = url
        ? `<img src="${escapeHtml(url)}" alt="">`
        : '<span><i class="bi bi-image d-block h3 mb-2"></i>No image selected</span>';
      document.getElementById('featured-status').textContent = status;
    };

    featuredImage?.addEventListener('input', () => {
      const value = featuredImage.value.trim();
      setFeaturedImage(value, value ? 'Preview updated from URL.' : 'Feature image cleared.');
    });

    const resetImageDialog = () => {
      ['image-file', 'image-url', 'image-alt', 'image-title', 'image-caption', 'image-link', 'image-width', 'image-height', 'image-class', 'image-credit'].forEach((id) => {
        const field = document.getElementById(id);

        if (field) {
          field.value = '';
        }
      });

      document.getElementById('image-align').value = '';
      document.getElementById('image-loading').value = 'lazy';
      document.getElementById('image-upload-preview').innerHTML = '';
      document.getElementById('image-upload-preview').classList.remove('active');
      document.getElementById('image-upload-status').textContent = 'Choose an image and it will upload automatically.';
      document.querySelectorAll('.editor-library-item.active').forEach((item) => item.classList.remove('active'));
    };

    const cleanEditorHtml = () => {
      const clone = editor.cloneNode(true);
      clone.querySelectorAll('[data-editor-ui]').forEach((node) => node.remove());
      clone.querySelectorAll('.is-selected').forEach((node) => node.classList.remove('is-selected'));
      clone.querySelectorAll('[data-media-hydrated]').forEach((node) => {
        node.removeAttribute('data-media-hydrated');
        node.removeAttribute('tabindex');
        node.removeAttribute('contenteditable');
      });

      return clone.innerHTML.trim();
    };

    const selectMediaBlock = (figure) => {
      editor.querySelectorAll('figure.post-media.is-selected').forEach((item) => {
        if (item !== figure) {
          item.classList.remove('is-selected');
        }
      });

      figure.classList.add('is-selected');
    };

    const updateMediaSizeControl = (figure) => {
      const slider = figure.querySelector('[data-editor-ui] input[type="range"]');
      const width = parseInt(figure.style.width || figure.getBoundingClientRect().width, 10);
      const editorWidth = Math.max(editor.getBoundingClientRect().width - 44, 1);
      const percent = Math.round((width / editorWidth) * 100);

      if (slider) {
        slider.value = Math.min(Math.max(percent, 20), 100);
      }
    };

    const setMediaAlignment = (figure, alignment) => {
      figure.classList.remove('align-left', 'align-center', 'align-right', 'align-full');

      if (alignment) {
        figure.classList.add(`align-${alignment}`);
      }

      if (alignment === 'full') {
        figure.style.width = '100%';
      }

      selectMediaBlock(figure);
      updateMediaSizeControl(figure);
      syncBodyFromEditor();
    };

    const moveMediaBlock = (figure, direction) => {
      const sibling = direction === 'up' ? figure.previousElementSibling : figure.nextElementSibling;

      if (! sibling) {
        return;
      }

      if (direction === 'up') {
        editor.insertBefore(figure, sibling);
      } else {
        editor.insertBefore(sibling, figure);
      }

      selectMediaBlock(figure);
      syncBodyFromEditor();
    };

    const hydrateMediaBlock = (figure) => {
      if (figure.dataset.mediaHydrated === 'true') {
        return;
      }

      figure.dataset.mediaHydrated = 'true';
      figure.setAttribute('tabindex', '0');
      figure.setAttribute('contenteditable', 'false');

      const controls = document.createElement('div');
      controls.className = 'editor-media-controls';
      controls.dataset.editorUi = 'true';
      controls.innerHTML = `
        <button type="button" class="editor-media-btn" data-media-action="up" title="Move up"><i class="bi bi-arrow-up"></i></button>
        <button type="button" class="editor-media-btn" data-media-action="down" title="Move down"><i class="bi bi-arrow-down"></i></button>
        <button type="button" class="editor-media-btn" data-media-align="left" title="Align left"><i class="bi bi-text-left"></i></button>
        <button type="button" class="editor-media-btn" data-media-align="center" title="Align center"><i class="bi bi-text-center"></i></button>
        <button type="button" class="editor-media-btn" data-media-align="right" title="Align right"><i class="bi bi-text-right"></i></button>
        <button type="button" class="editor-media-btn" data-media-align="full" title="Full width"><i class="bi bi-arrows-angle-expand"></i></button>
        <input class="editor-media-size" type="range" min="20" max="100" value="100" title="Resize image">
        <button type="button" class="editor-media-btn" data-media-action="remove" title="Remove image"><i class="bi bi-trash"></i></button>
      `;

      const handle = document.createElement('span');
      handle.className = 'editor-media-handle';
      handle.dataset.editorUi = 'true';

      figure.prepend(controls);
      figure.appendChild(handle);
      updateMediaSizeControl(figure);

      figure.addEventListener('click', (event) => {
        event.stopPropagation();
        selectMediaBlock(figure);
      });

      figure.addEventListener('focus', () => selectMediaBlock(figure));

      controls.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        const action = event.target.closest('[data-media-action]')?.dataset.mediaAction;
        const alignment = event.target.closest('[data-media-align]')?.dataset.mediaAlign;

        if (alignment) {
          setMediaAlignment(figure, alignment);
        }

        if (action === 'up' || action === 'down') {
          moveMediaBlock(figure, action);
        }

        if (action === 'remove') {
          figure.remove();
          syncBodyFromEditor();
        }
      });

      const slider = controls.querySelector('input[type="range"]');
      slider.addEventListener('input', () => {
        figure.style.width = `${slider.value}%`;
        figure.classList.remove('align-full');
        selectMediaBlock(figure);
        syncBodyFromEditor();
      });

      let resizeState = null;

      handle.addEventListener('mousedown', (event) => {
        event.preventDefault();
        event.stopPropagation();
        selectMediaBlock(figure);
        resizeState = {
          startX: event.clientX,
          startWidth: figure.getBoundingClientRect().width,
          editorWidth: Math.max(editor.getBoundingClientRect().width - 44, 1),
        };
        document.body.style.userSelect = 'none';
      });

      document.addEventListener('mousemove', (event) => {
        if (! resizeState) {
          return;
        }

        const nextWidth = resizeState.startWidth + (event.clientX - resizeState.startX);
        const percent = Math.min(Math.max(Math.round((nextWidth / resizeState.editorWidth) * 100), 20), 100);
        figure.style.width = `${percent}%`;
        figure.classList.remove('align-full');
        slider.value = percent;
        syncBodyFromEditor();
      });

      document.addEventListener('mouseup', () => {
        if (resizeState) {
          resizeState = null;
          document.body.style.userSelect = '';
          syncBodyFromEditor();
        }
      });
    };

    const hydrateEditorMedia = () => {
      editor.querySelectorAll('figure.post-media').forEach(hydrateMediaBlock);
    };

    const saveSelection = () => {
      const selection = window.getSelection();

      if (selection && selection.rangeCount > 0 && editor?.contains(selection.anchorNode)) {
        savedRange = selection.getRangeAt(0).cloneRange();
      }
    };

    const placeCursorAtEditorEnd = () => {
      if (! editor) {
        return;
      }

      focusEditor();

      const range = document.createRange();
      range.selectNodeContents(editor);
      range.collapse(false);

      const selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(range);
      savedRange = range.cloneRange();
    };

    const restoreSelection = () => {
      if (! savedRange || ! editor?.contains(savedRange.startContainer)) {
        placeCursorAtEditorEnd();
        return;
      }

      const selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(savedRange);
    };

    const selectedText = () => {
      restoreSelection();
      return window.getSelection()?.toString().trim() || '';
    };

    const openDialog = (id) => {
      saveSelection();
      const dialog = document.getElementById(id);
      dialog?.classList.add('active');
      dialog?.setAttribute('aria-hidden', 'false');
      dialog?.querySelector('input, select, textarea, button')?.focus();
    };

    const closeDialog = (dialog) => {
      dialog?.classList.remove('active');
      dialog?.setAttribute('aria-hidden', 'true');
      focusEditor();
    };

    const syncBodyFromEditor = () => {
      if (! bodyTextarea || ! editor) {
        return;
      }

      bodyTextarea.value = cleanEditorHtml();
      bodyTextarea.dispatchEvent(new Event('input', { bubbles: true }));
      updateWordCount();
    };

    const syncEditorFromSource = () => {
      editor.innerHTML = source.value;
      syncBodyFromEditor();
    };

    const updateWordCount = () => {
      if (! editor || ! wordCounter) {
        return;
      }

      const words = editor.innerText.trim().split(/\s+/).filter(Boolean);
      wordCounter.textContent = words.length;
    };

    const focusEditor = () => {
      editor?.focus();
    };

    const runCommand = (command, value = null) => {
      focusEditor();
      document.execCommand(command, false, value);
      syncBodyFromEditor();
      refreshActiveButtons();
    };

    const insertHtml = (html) => {
      if (editorShell.classList.contains('source-mode')) {
        editorShell.classList.remove('source-mode');
        syncEditorFromSource();
      }

      restoreSelection();
      const before = editor.innerHTML;
      const inserted = document.execCommand('insertHTML', false, html);

      if (! inserted || editor.innerHTML === before) {
        const selection = window.getSelection();
        const range = selection?.rangeCount ? selection.getRangeAt(0) : null;
        const template = document.createElement('template');
        template.innerHTML = html.trim();
        const fragment = template.content;
        const lastNode = fragment.lastChild;

        if (range && editor.contains(range.commonAncestorContainer)) {
          range.deleteContents();
          range.insertNode(fragment);

          if (lastNode) {
            range.setStartAfter(lastNode);
            range.collapse(true);
            selection.removeAllRanges();
            selection.addRange(range);
          }
        } else {
          editor.appendChild(fragment);
          placeCursorAtEditorEnd();
        }
      }

      syncBodyFromEditor();
      saveSelection();
    };

    const insertBlockHtml = (html) => {
      if (editorShell.classList.contains('source-mode')) {
        editorShell.classList.remove('source-mode');
        syncEditorFromSource();
      }

      const template = document.createElement('template');
      template.innerHTML = html.trim();
      const block = template.content.firstElementChild;

      if (! block) {
        return;
      }

      editor.appendChild(block);
      hydrateEditorMedia();

      const mediaBlock = block.matches('figure.post-media') ? block : block.querySelector('figure.post-media');

      if (mediaBlock) {
        selectMediaBlock(mediaBlock);
      }

      if (! editor.lastElementChild?.matches('p')) {
        editor.insertAdjacentHTML('beforeend', '<p><br></p>');
      }

      syncBodyFromEditor();
      block.scrollIntoView({ behavior: 'smooth', block: 'center' });
      placeCursorAtEditorEnd();
    };

    const closestSelectedLink = () => {
      restoreSelection();
      const selection = window.getSelection();
      let node = selection?.anchorNode;

      if (node?.nodeType === Node.TEXT_NODE) {
        node = node.parentElement;
      }

      return node?.closest?.('a') || null;
    };

    const embedUrlFromVideo = (url) => {
      const value = url.trim();
      const youtubeMatch = value.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([A-Za-z0-9_-]+)/);
      const vimeoMatch = value.match(/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/);
      const isPrivacyMode = checked('video-privacy');

      if (youtubeMatch) {
        const host = isPrivacyMode ? 'https://www.youtube-nocookie.com' : 'https://www.youtube.com';
        const params = new URLSearchParams();

        if (checked('video-autoplay')) params.set('autoplay', '1');
        if (checked('video-muted')) params.set('mute', '1');
        if (! checked('video-controls')) params.set('controls', '0');

        return `${host}/embed/${youtubeMatch[1]}${params.toString() ? `?${params}` : ''}`;
      }

      if (vimeoMatch) {
        const params = new URLSearchParams();

        if (checked('video-autoplay')) params.set('autoplay', '1');
        if (checked('video-muted')) params.set('muted', '1');
        if (! checked('video-controls')) params.set('controls', '0');

        return `https://player.vimeo.com/video/${vimeoMatch[1]}${params.toString() ? `?${params}` : ''}`;
      }

      return value;
    };

    const refreshActiveButtons = () => {
      document.querySelectorAll('[data-command]').forEach((button) => {
        const command = button.dataset.command;
        const supportsState = ['bold', 'italic', 'underline', 'strikeThrough', 'insertUnorderedList', 'insertOrderedList', 'justifyLeft', 'justifyCenter', 'justifyRight'].includes(command);

        if (supportsState && document.queryCommandState(command)) {
          button.classList.add('active');
        } else {
          button.classList.remove('active');
        }
      });
    };

    document.querySelectorAll('[data-command]').forEach((button) => {
      button.addEventListener('click', () => runCommand(button.dataset.command));
    });

    document.querySelectorAll('.wysiwyg-toolbar button').forEach((button) => {
      button.addEventListener('mousedown', (event) => {
        event.preventDefault();
        saveSelection();
      });
    });

    document.querySelector('[data-format-block]')?.addEventListener('change', (event) => {
      runCommand('formatBlock', event.target.value);
      event.target.value = 'p';
    });

    document.querySelectorAll('[data-color-command]').forEach((input) => {
      input.addEventListener('input', () => runCommand(input.dataset.colorCommand, input.value));
    });

    document.querySelector('[data-action="link"]')?.addEventListener('click', () => {
      const link = closestSelectedLink();
      document.getElementById('link-url').value = link?.getAttribute('href') || '';
      document.getElementById('link-text').value = link?.textContent?.trim() || selectedText();
      document.getElementById('link-title').value = link?.getAttribute('title') || '';
      document.getElementById('link-target').value = link?.getAttribute('target') || '';
      document.getElementById('link-rel').value = link?.getAttribute('rel') || '';
      document.getElementById('link-aria').value = link?.getAttribute('aria-label') || '';
      document.getElementById('link-class').value = link?.getAttribute('class') || '';
      document.getElementById('link-download').checked = link?.hasAttribute('download') || false;
      document.getElementById('link-sponsored').checked = (link?.getAttribute('rel') || '').includes('sponsored');
      document.getElementById('link-nofollow').checked = (link?.getAttribute('rel') || '').includes('nofollow');
      openDialog('link-dialog');
    });

    document.getElementById('link-apply')?.addEventListener('click', () => {
      const url = valueOf('link-url');
      const text = valueOf('link-text') || selectedText() || url;
      const relParts = valueOf('link-rel').split(/\s+/).filter(Boolean);

      if (checked('link-sponsored')) relParts.push('sponsored');
      if (checked('link-nofollow')) relParts.push('nofollow');
      if (valueOf('link-target') === '_blank') relParts.push('noopener', 'noreferrer');

      const rel = [...new Set(relParts)].join(' ');
      const html = `<a href="${escapeHtml(url)}"${attr('title', valueOf('link-title'))}${attr('target', valueOf('link-target'))}${attr('rel', rel)}${attr('aria-label', valueOf('link-aria'))}${attr('class', valueOf('link-class'))}${checked('link-download') ? ' download' : ''}>${escapeHtml(text)}</a>`;

      if (url) {
        insertHtml(html);
      }

      closeDialog(document.getElementById('link-dialog'));
    });

    document.getElementById('link-remove')?.addEventListener('click', () => {
      restoreSelection();
      document.execCommand('unlink');
      syncBodyFromEditor();
      closeDialog(document.getElementById('link-dialog'));
    });

    const uploadEditorImage = async () => {
      const fileInput = document.getElementById('image-file');
      const status = document.getElementById('image-upload-status');
      const preview = document.getElementById('image-upload-preview');
      const file = fileInput?.files?.[0];

      if (! file) {
        status.textContent = 'Choose an image from your machine first.';
        return;
      }

      if (preview) {
        const previewUrl = URL.createObjectURL(file);
        preview.innerHTML = `<img src="${previewUrl}" alt="">`;
        preview.classList.add('active');
      }

      const payload = new FormData();
      payload.append('image', file);
      payload.append('alt_text', valueOf('image-alt'));
      payload.append('caption', valueOf('image-caption'));
      status.textContent = 'Uploading image automatically...';

      try {
        const response = await fetch(editorImageUploadUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: payload,
        });

        if (! response.ok) {
          throw new Error('Upload failed');
        }

        const image = await response.json();
        document.getElementById('image-url').value = image.url;

        if (preview) {
          preview.innerHTML = `<img src="${escapeHtml(image.url)}" alt="">`;
          preview.classList.add('active');
        }

        if (! valueOf('image-alt')) {
          document.getElementById('image-alt').value = image.alt_text || image.name || '';
        }

        if (image.caption && ! valueOf('image-caption')) {
          document.getElementById('image-caption').value = image.caption;
        }

        status.textContent = 'Image uploaded. Review attributes, then insert.';
      } catch (error) {
        status.textContent = 'Upload failed. Check file type and size, then try again.';
      }
    };

    const uploadFeatureImage = async () => {
      const fileInput = document.getElementById('featured_image_file');
      const status = document.getElementById('featured-status');
      const file = fileInput?.files?.[0];

      if (! file) {
        status.textContent = 'Choose an image first.';
        return;
      }

      setFeaturedImage(URL.createObjectURL(file), 'Uploading feature image...');

      const payload = new FormData();
      payload.append('image', file);

      try {
        const response = await fetch(editorImageUploadUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: payload,
        });

        if (! response.ok) {
          throw new Error('Upload failed');
        }

        const image = await response.json();
        setFeaturedImage(image.url, 'Feature image uploaded and selected.');
      } catch (error) {
        status.textContent = 'Upload failed. Check file type and size, then try again.';
      }
    };

    document.querySelector('[data-action="image"]')?.addEventListener('click', () => {
      imageDialogMode = 'body';
      document.getElementById('image-dialog-title').innerHTML = '<i class="bi bi-image"></i> Image Settings';
      document.getElementById('image-apply').innerHTML = '<i class="bi bi-check2"></i> Insert Image';
      resetImageDialog();
      openDialog('image-dialog');

      if (! imageLibraryLoaded) {
        loadImageLibrary({ reset: true });
      }
    });

    document.getElementById('image-file')?.addEventListener('change', uploadEditorImage);

    document.getElementById('featured-browse')?.addEventListener('click', () => {
      document.getElementById('featured_image_file')?.click();
    });

    document.getElementById('featured_image_file')?.addEventListener('change', uploadFeatureImage);

    document.getElementById('featured-library')?.addEventListener('click', () => {
      imageDialogMode = 'featured';
      document.getElementById('image-dialog-title').innerHTML = '<i class="bi bi-image"></i> Feature Image';
      document.getElementById('image-apply').innerHTML = '<i class="bi bi-check2"></i> Use Feature Image';
      resetImageDialog();
      openDialog('image-dialog');

      if (! imageLibraryLoaded) {
        loadImageLibrary({ reset: true });
      }
    });

    document.getElementById('featured-refresh')?.addEventListener('click', () => {
      setFeaturedImage(valueOf('featured_image'), valueOf('featured_image') ? 'Preview refreshed.' : 'No feature image selected.');
    });

    document.getElementById('featured-clear')?.addEventListener('click', () => {
      setFeaturedImage('', 'Feature image cleared.');
    });

    document.getElementById('seo-generate')?.addEventListener('click', () => {
      generateSeoFields({ force: true });
    });

    const selectLibraryImage = (item) => {
      document.querySelectorAll('.editor-library-item.active').forEach((activeItem) => activeItem.classList.remove('active'));
      item.classList.add('active');
      document.getElementById('image-url').value = item.dataset.mediaUrl || '';
      document.getElementById('image-alt').value = item.dataset.mediaAlt || item.dataset.mediaName || '';
      document.getElementById('image-caption').value = item.dataset.mediaCaption || '';
      document.getElementById('image-upload-preview').innerHTML = `<img src="${escapeHtml(item.dataset.mediaUrl || '')}" alt="">`;
      document.getElementById('image-upload-preview').classList.add('active');
      document.getElementById('image-upload-status').textContent = 'Media Library image selected. Review attributes, then insert.';
    };

    const renderLibraryItems = (items, append = false) => {
      const grid = document.getElementById('image-library-grid');

      if (! append) {
        grid.innerHTML = '';
      }

      if (! items.length && ! append) {
        grid.innerHTML = '<div class="editor-dialog-note">No images found. Browse a local image above to add one.</div>';
        return;
      }

      items.forEach((media) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'editor-library-item';
        button.dataset.mediaUrl = media.url || '';
        button.dataset.mediaName = media.name || '';
        button.dataset.mediaAlt = media.alt_text || '';
        button.dataset.mediaCaption = media.caption || '';
        button.innerHTML = `<img src="${escapeHtml(media.url || '')}" alt=""><span>${escapeHtml(media.name || 'Untitled image')}</span>`;
        button.addEventListener('click', () => selectLibraryImage(button));
        grid.appendChild(button);
      });
    };

    const loadImageLibrary = async ({ reset = false } = {}) => {
      const status = document.getElementById('image-library-status');
      const moreButton = document.getElementById('image-library-more');

      if (reset) {
        imageLibraryPage = 1;
        imageLibraryHasMore = true;
      }

      if (! imageLibraryHasMore && ! reset) {
        return;
      }

      status.textContent = imageLibraryPage === 1 ? 'Loading latest 20 images...' : 'Loading more images...';
      moreButton.disabled = true;

      try {
        const url = new URL(editorMediaUrl, window.location.origin);
        url.searchParams.set('type', 'images');
        url.searchParams.set('per_page', '20');
        url.searchParams.set('page', imageLibraryPage.toString());

        if (imageLibrarySearch) {
          url.searchParams.set('search', imageLibrarySearch);
        }

        const response = await fetch(url, { headers: { Accept: 'application/json' } });

        if (! response.ok) {
          throw new Error('Media request failed');
        }

        const payload = await response.json();
        renderLibraryItems(payload.data || [], imageLibraryPage > 1);
        imageLibraryLoaded = true;
        imageLibraryHasMore = payload.meta.current_page < payload.meta.last_page;
        status.textContent = `${payload.meta.total} image${payload.meta.total === 1 ? '' : 's'} found. Showing ${Math.min(payload.meta.current_page * payload.meta.per_page, payload.meta.total)}.`;
        moreButton.hidden = ! imageLibraryHasMore;
        moreButton.disabled = false;
        imageLibraryPage += 1;
      } catch (error) {
        status.textContent = 'Could not load Media Library. Try again.';
        moreButton.hidden = false;
        moreButton.disabled = false;
      }
    };

    document.getElementById('image-library-search')?.addEventListener('input', (event) => {
      clearTimeout(imageLibraryTimer);
      imageLibrarySearch = event.target.value.trim();
      imageLibraryTimer = setTimeout(() => loadImageLibrary({ reset: true }), 300);
    });

    document.getElementById('image-library-more')?.addEventListener('click', () => loadImageLibrary());

    document.getElementById('image-apply')?.addEventListener('click', () => {
      const url = valueOf('image-url');

      if (! url) {
        return;
      }

      const width = valueOf('image-width');
      const height = valueOf('image-height');
      const align = valueOf('image-align');
      const caption = valueOf('image-caption');
      const credit = valueOf('image-credit');
      const imageClass = [valueOf('image-class'), align ? `align-${align}` : ''].filter(Boolean).join(' ');
      const styleParts = [];

      if (width) styleParts.push(`width:${escapeHtml(width)};`);
      if (height) styleParts.push(`height:${escapeHtml(height)};`);
      if (align === 'full') styleParts.push('width:100%;');

      const image = `<img src="${escapeHtml(url)}"${attr('alt', valueOf('image-alt'))}${attr('title', valueOf('image-title'))}${attr('class', imageClass)}${attr('loading', valueOf('image-loading'))}${styleParts.length ? attr('style', styleParts.join('')) : ''}>`;
      const linkedImage = valueOf('image-link') ? `<a href="${escapeHtml(valueOf('image-link'))}">${image}</a>` : image;
      const captionHtml = caption || credit ? `<figcaption>${caption ? escapeHtml(caption) : ''}${credit ? `<span> ${escapeHtml(credit)}</span>` : ''}</figcaption>` : '';

      if (imageDialogMode === 'featured') {
        setFeaturedImage(url, 'Feature image selected.');
      } else {
        insertBlockHtml(`<figure class="${escapeHtml(['post-media', align ? `align-${align}` : ''].filter(Boolean).join(' '))}">${linkedImage}${captionHtml}</figure>`);
        document.getElementById('image-upload-status').textContent = 'Image inserted into the editor.';
      }

      closeDialog(document.getElementById('image-dialog'));
      resetImageDialog();
    });

    document.querySelector('[data-action="video"]')?.addEventListener('click', () => openDialog('video-dialog'));

    document.getElementById('video-apply')?.addEventListener('click', () => {
      const url = valueOf('video-url');

      if (! url) {
        return;
      }

      const embedUrl = embedUrlFromVideo(url);
      const ratio = valueOf('video-ratio') || '56.25%';
      const maxWidth = valueOf('video-width');
      const wrapperStyle = `position:relative;padding-bottom:${escapeHtml(ratio)};height:0;margin:18px 0;${maxWidth ? `max-width:${escapeHtml(maxWidth)};` : ''}`;
      const iframe = `<iframe src="${escapeHtml(embedUrl)}"${attr('title', valueOf('video-title') || 'Embedded video')}${attr('loading', valueOf('video-loading'))} style="position:absolute;inset:0;width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
      const caption = valueOf('video-caption') ? `<figcaption>${escapeHtml(valueOf('video-caption'))}</figcaption>` : '';

      insertBlockHtml(`<figure class="${escapeHtml(['post-video', valueOf('video-class')].filter(Boolean).join(' '))}"><div style="${wrapperStyle}">${iframe}</div>${caption}</figure>`);
      closeDialog(document.getElementById('video-dialog'));
    });

    document.querySelector('[data-action="table"]')?.addEventListener('click', () => openDialog('table-dialog'));

    document.getElementById('table-apply')?.addEventListener('click', () => {
      const rows = Math.min(Math.max(parseInt(valueOf('table-rows') || '3', 10), 1), 24);
      const columns = Math.min(Math.max(parseInt(valueOf('table-columns') || '3', 10), 1), 12);
      const hasHeaderRow = checked('table-header-row');
      const hasHeaderColumn = checked('table-header-column');
      const style = valueOf('table-style') || 'clean';
      const align = valueOf('table-align');
      const caption = valueOf('table-caption');
      const ariaLabel = valueOf('table-summary');
      const tableClass = ['editor-table', `table-${style}`, align ? `align-${align}` : ''].filter(Boolean).join(' ');

      const tableRows = Array.from({ length: rows }).map((_, rowIndex) => {
        const cells = Array.from({ length: columns }).map((__, columnIndex) => {
          const isHeader = (hasHeaderRow && rowIndex === 0) || (hasHeaderColumn && columnIndex === 0);
          const tag = isHeader ? 'th' : 'td';
          const scope = isHeader ? attr('scope', rowIndex === 0 ? 'col' : 'row') : '';
          const content = isHeader ? `Header ${rowIndex + 1}.${columnIndex + 1}` : 'Cell';
          const padding = style === 'compact' ? '7px 8px' : '10px 12px';
          const background = hasHeaderRow && rowIndex === 0 ? 'background:#f8faf7;font-weight:800;color:#17472d;' : '';

          return `<${tag}${scope} style="border:1px solid #d7ded9;padding:${padding};${background}">${content}</${tag}>`;
        }).join('');

        return `<tr>${cells}</tr>`;
      }).join('');

      const captionHtml = caption ? `<caption style="caption-side:top;text-align:left;color:#17472d;font-weight:800;margin-bottom:8px;">${escapeHtml(caption)}</caption>` : '';
      const stripedStyle = style === 'striped' ? '<style scoped>tbody tr:nth-child(even){background:#f8faf7;}</style>' : '';
      const table = `${stripedStyle}<table class="${escapeHtml(tableClass)}"${attr('aria-label', ariaLabel || caption)} style="width:100%;border-collapse:collapse;margin:18px 0;">${captionHtml}<tbody>${tableRows}</tbody></table>`;
      const html = checked('table-responsive') ? `<div class="table-responsive" style="overflow-x:auto;">${table}</div>` : table;

      insertBlockHtml(html);
      closeDialog(document.getElementById('table-dialog'));
    });

    document.querySelector('[data-action="source"]')?.addEventListener('click', (event) => {
      const isSourceMode = editorShell.classList.toggle('source-mode');
      event.currentTarget.classList.toggle('active', isSourceMode);

      if (isSourceMode) {
        source.value = editor.innerHTML.trim();
        source.focus();
      } else {
        syncEditorFromSource();
        focusEditor();
      }
    });

    document.querySelector('[data-action="fullscreen"]')?.addEventListener('click', (event) => {
      editorShell.classList.toggle('fullscreen');
      event.currentTarget.classList.toggle('active', editorShell.classList.contains('fullscreen'));
      focusEditor();
    });

    document.querySelectorAll('[data-dialog-close]').forEach((button) => {
      button.addEventListener('click', () => closeDialog(button.closest('.editor-dialog-backdrop')));
    });

    document.querySelectorAll('.editor-dialog-backdrop').forEach((dialog) => {
      dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
          closeDialog(dialog);
        }
      });
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        document.querySelectorAll('.editor-dialog-backdrop.active').forEach(closeDialog);
      }
    });

    document.querySelector('[data-action="unlink"]')?.addEventListener('click', () => {
      const link = closestSelectedLink();

      if (link) {
        document.getElementById('link-url').value = link.getAttribute('href') || '';
        document.getElementById('link-text').value = link.textContent?.trim() || '';
        document.getElementById('link-title').value = link.getAttribute('title') || '';
        document.getElementById('link-target').value = link.getAttribute('target') || '';
        document.getElementById('link-rel').value = link.getAttribute('rel') || '';
        document.getElementById('link-aria').value = link.getAttribute('aria-label') || '';
        document.getElementById('link-class').value = link.getAttribute('class') || '';
        document.getElementById('link-download').checked = link.hasAttribute('download');
        document.getElementById('link-sponsored').checked = (link.getAttribute('rel') || '').includes('sponsored');
        document.getElementById('link-nofollow').checked = (link.getAttribute('rel') || '').includes('nofollow');
        openDialog('link-dialog');
      } else {
        runCommand('unlink');
      }
    });

    editor?.addEventListener('input', syncBodyFromEditor);
    editor?.addEventListener('click', (event) => {
      if (! event.target.closest('figure.post-media')) {
        editor.querySelectorAll('figure.post-media.is-selected').forEach((figure) => figure.classList.remove('is-selected'));
        syncBodyFromEditor();
      }
    });
    editor?.addEventListener('keyup', () => {
      saveSelection();
      refreshActiveButtons();
    });
    editor?.addEventListener('mouseup', () => {
      saveSelection();
      refreshActiveButtons();
    });

    document.addEventListener('selectionchange', () => {
      saveSelection();
    });

    source?.addEventListener('input', () => {
      bodyTextarea.value = source.value;
      bodyTextarea.dispatchEvent(new Event('input', { bubbles: true }));
    });

    form?.addEventListener('submit', () => {
      if (editorShell.classList.contains('source-mode')) {
        syncEditorFromSource();
      } else {
        syncBodyFromEditor();
      }

      generateSeoFields({ force: false });
    });

    hydrateEditorMedia();
    syncBodyFromEditor();
  </script>
@endpush
