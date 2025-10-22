# Tag and Release Management Instructions (GitHub)

## Overview
This document outlines the complete workflow for managing Git tags, changelogs, and GitHub releases in the repository.

## Workflow for Creating Tags and Releases

When the user requests to create a new tag or release, follow these steps in order:

### 1. Check Latest Tag
First, identify the current latest tag:
```bash
git describe --tags --abbrev=0
```

Inform the user of the current tag version and suggest the next version based on semantic versioning (e.g., if current is v1.49.1, suggest v1.49.2 for patch, v1.50.0 for minor, or v2.0.0 for major).

### 2. Generate Changelog
List all commits between the latest tag and HEAD to create the changelog:
```bash
git log <latest-tag>..HEAD --oneline
```

Extract and format the commit messages into a structured changelog with categories:
- **Breaking Changes**: Any breaking changes
- **Features**: New features or enhancements
- **Bug Fixes**: Bug fixes and corrections
- **Documentation**: Documentation updates
- **Internal**: Internal changes, refactoring, etc.

### 3. Create Annotated Tag
Create an annotated tag with the commit messages as the description:
```bash
git tag -a <new-tag> -m "<changelog-text>"
```

**Important for PowerShell**: Multi-line messages work differently in PowerShell. Use the syntax:
```powershell
git tag -a v1.49.2 -m "line 1
line 2
line 3"
```

### 4. Push Tag to Remote
Push the newly created tag to the remote repository:
```bash
git push origin <new-tag>
```

### 5. Create GitHub Release
After the tag is pushed, create a GitHub Release using one of these methods:

#### Option A: Using GitHub CLI (if available)
```bash
gh release create <tag> --title "<tag>" --notes "<formatted-changelog>"
```

#### Option B: Manual Creation
If `gh` CLI is not available, provide the user with:
1. Direct URL: `https://github.com/<owner>/<repo>/releases/new?tag=<tag>`
2. Formatted Markdown changelog to copy and paste

**Changelog Format for Release:**
```markdown
## What's Changed

- **Component/Area**: Brief description of change
- **Component/Area**: Brief description of change
- ...

**Full Changelog**: https://github.com/<owner>/<repo>/compare/<previous-tag>...<new-tag>
```

## Important Notes

### Tag vs Release
- **Git Tag**: A reference to a specific commit in the repository. Can have a message but displays as plain text.
- **GitHub Release**: A rich presentation layer built on top of a tag. Supports Markdown formatting, file attachments, and is more visible to users.
- Always create BOTH: an annotated tag (for git history) AND a GitHub release (for user-facing changelog).

### PowerShell Considerations
- PowerShell does not support `&&` for command chaining. Run commands sequentially.
- Multi-line strings in git commands work but must be properly formatted.
- Use `;` to separate commands on the same line if needed.

### Versioning
Follow semantic versioning (SemVer):
- **Major (v2.0.0)**: Breaking changes
- **Minor (v1.50.0)**: New features, backward compatible
- **Patch (v1.49.2)**: Bug fixes, backward compatible

## Example Complete Workflow

```powershell
# 1. Check latest tag
git describe --tags --abbrev=0
# Output: v1.49.1

# 2. Get commits since last tag
git log v1.49.1..HEAD --oneline
# Output: List of commits

# 3. Create annotated tag
git tag -a v1.49.2 -m "fix: refactor dialog methods
fix: enhance Colorpicker component
fix: add fallback image support"

# 4. Push tag
git push origin v1.49.2

# 5. Create release (if gh CLI available)
gh release create v1.49.2 --title "v1.49.2" --notes "## What's Changed

- **Dialog**: Refactor methods for better clarity
- **Colorpicker**: Support wire:model and x-model bindings
- **Avatar**: Add fallback image support

**Full Changelog**: https://github.com/owner/repo/compare/v1.49.1...v1.49.2"
```

## Checklist
Before completing a tag/release creation, verify:
- [ ] Latest tag identified and next version confirmed with user
- [ ] Changelog generated from commits
- [ ] Annotated tag created with description
- [ ] Tag pushed to remote repository
- [ ] GitHub Release created with formatted Markdown changelog
- [ ] Release URL verified and working