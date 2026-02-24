<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::public')] #[Title('Vim Cheatsheet')] class extends Component
{
    public string $search = '';

    /**
     * @return list<array{name: string, commands: list<array{keys: list<string>, description: string}>}>
     */
    #[Computed]
    public function categories(): array
    {
        $all = $this->allCommands();

        if (trim($this->search) === '') {
            return $all;
        }

        $term = strtolower(trim($this->search));

        return collect($all)
            ->map(function (array $category) use ($term): array {
                $commands = array_values(array_filter(
                    $category['commands'],
                    fn (array $cmd): bool => str_contains(strtolower(implode(' ', $cmd['keys'])), $term)
                        || str_contains(strtolower($cmd['description']), $term),
                ));

                return [...$category, 'commands' => $commands];
            })
            ->filter(fn (array $category): bool => count($category['commands']) > 0)
            ->values()
            ->all();
    }

    #[Computed]
    public function totalMatching(): int
    {
        return collect($this->categories)->sum(fn (array $cat): int => count($cat['commands']));
    }

    /**
     * @return list<array{name: string, commands: list<array{keys: list<string>, description: string}>}>
     */
    private function allCommands(): array
    {
        return [
            [
                'name' => 'Navigation',
                'commands' => [
                    ['keys' => ['h'], 'description' => 'Move cursor left'],
                    ['keys' => ['j'], 'description' => 'Move cursor down'],
                    ['keys' => ['k'], 'description' => 'Move cursor up'],
                    ['keys' => ['l'], 'description' => 'Move cursor right'],
                    ['keys' => ['w'], 'description' => 'Jump to start of next word'],
                    ['keys' => ['b'], 'description' => 'Jump to start of previous word'],
                    ['keys' => ['e'], 'description' => 'Jump to end of current word'],
                    ['keys' => ['W'], 'description' => 'Jump forward by WORD (whitespace-separated)'],
                    ['keys' => ['B'], 'description' => 'Jump backward by WORD'],
                    ['keys' => ['0'], 'description' => 'Move to start of line'],
                    ['keys' => ['^'], 'description' => 'Move to first non-blank character of line'],
                    ['keys' => ['$'], 'description' => 'Move to end of line'],
                    ['keys' => ['gg'], 'description' => 'Go to first line of file'],
                    ['keys' => ['G'], 'description' => 'Go to last line of file'],
                    ['keys' => ['{n}G'], 'description' => 'Go to line n (e.g. 42G)'],
                    ['keys' => ['Ctrl+d'], 'description' => 'Scroll half-page down'],
                    ['keys' => ['Ctrl+u'], 'description' => 'Scroll half-page up'],
                    ['keys' => ['Ctrl+f'], 'description' => 'Scroll one full page forward'],
                    ['keys' => ['Ctrl+b'], 'description' => 'Scroll one full page back'],
                    ['keys' => ['H'], 'description' => 'Move to top of visible screen'],
                    ['keys' => ['M'], 'description' => 'Move to middle of visible screen'],
                    ['keys' => ['L'], 'description' => 'Move to bottom of visible screen'],
                    ['keys' => ['zz'], 'description' => 'Center cursor on screen'],
                    ['keys' => ['zt'], 'description' => 'Scroll so cursor is at top'],
                    ['keys' => ['zb'], 'description' => 'Scroll so cursor is at bottom'],
                    ['keys' => ['%'], 'description' => 'Jump to matching bracket or delimiter'],
                    ['keys' => ['f', '{char}'], 'description' => 'Jump to next occurrence of char on line'],
                    ['keys' => ['F', '{char}'], 'description' => 'Jump to previous occurrence of char on line'],
                    ['keys' => ['t', '{char}'], 'description' => 'Jump to just before next char'],
                    ['keys' => ['T', '{char}'], 'description' => 'Jump to just after previous char'],
                    ['keys' => [';'], 'description' => 'Repeat last f/F/t/T motion forward'],
                    ['keys' => [','], 'description' => 'Repeat last f/F/t/T motion backward'],
                ],
            ],
            [
                'name' => 'Insert Mode',
                'commands' => [
                    ['keys' => ['i'], 'description' => 'Insert before cursor'],
                    ['keys' => ['I'], 'description' => 'Insert at start of line'],
                    ['keys' => ['a'], 'description' => 'Append after cursor'],
                    ['keys' => ['A'], 'description' => 'Append at end of line'],
                    ['keys' => ['o'], 'description' => 'Open new line below and enter insert mode'],
                    ['keys' => ['O'], 'description' => 'Open new line above and enter insert mode'],
                    ['keys' => ['s'], 'description' => 'Delete character and enter insert mode'],
                    ['keys' => ['S'], 'description' => 'Delete entire line and enter insert mode'],
                    ['keys' => ['Esc'], 'description' => 'Return to normal mode'],
                    ['keys' => ['Ctrl+['], 'description' => 'Return to normal mode (alternative to Esc)'],
                    ['keys' => ['Ctrl+c'], 'description' => 'Return to normal mode (skips autocmds)'],
                    ['keys' => ['Ctrl+h'], 'description' => 'Delete previous character (like Backspace)'],
                    ['keys' => ['Ctrl+w'], 'description' => 'Delete previous word'],
                    ['keys' => ['Ctrl+u'], 'description' => 'Delete to beginning of line'],
                ],
            ],
            [
                'name' => 'Editing',
                'commands' => [
                    ['keys' => ['x'], 'description' => 'Delete character under cursor'],
                    ['keys' => ['X'], 'description' => 'Delete character before cursor'],
                    ['keys' => ['dd'], 'description' => 'Delete (cut) current line'],
                    ['keys' => ['D'], 'description' => 'Delete from cursor to end of line'],
                    ['keys' => ['dw'], 'description' => 'Delete to end of word'],
                    ['keys' => ['d0'], 'description' => 'Delete to start of line'],
                    ['keys' => ['{n}dd'], 'description' => 'Delete n lines'],
                    ['keys' => ['yy'], 'description' => 'Yank (copy) current line'],
                    ['keys' => ['yw'], 'description' => 'Yank to end of word'],
                    ['keys' => ['y$'], 'description' => 'Yank to end of line'],
                    ['keys' => ['{n}yy'], 'description' => 'Yank n lines'],
                    ['keys' => ['p'], 'description' => 'Paste after cursor / below line'],
                    ['keys' => ['P'], 'description' => 'Paste before cursor / above line'],
                    ['keys' => ['u'], 'description' => 'Undo last change'],
                    ['keys' => ['Ctrl+r'], 'description' => 'Redo'],
                    ['keys' => ['r', '{char}'], 'description' => 'Replace single character under cursor'],
                    ['keys' => ['R'], 'description' => 'Enter replace mode (overwrite characters)'],
                    ['keys' => ['cc'], 'description' => 'Change entire line'],
                    ['keys' => ['cw'], 'description' => 'Change to end of word'],
                    ['keys' => ['C'], 'description' => 'Change from cursor to end of line'],
                    ['keys' => ['ci', '{char}'], 'description' => 'Change inside delimiter (e.g. ci" for inside quotes)'],
                    ['keys' => ['ca', '{char}'], 'description' => 'Change around delimiter (includes the delimiters)'],
                    ['keys' => ['di', '{char}'], 'description' => 'Delete inside delimiter'],
                    ['keys' => ['da', '{char}'], 'description' => 'Delete around delimiter'],
                    ['keys' => ['.'], 'description' => 'Repeat last change'],
                    ['keys' => ['~'], 'description' => 'Toggle case of character under cursor'],
                    ['keys' => ['gu', '{motion}'], 'description' => 'Lowercase text over motion'],
                    ['keys' => ['gU', '{motion}'], 'description' => 'Uppercase text over motion'],
                    ['keys' => ['>>'], 'description' => 'Indent current line'],
                    ['keys' => ['<<'], 'description' => 'Outdent current line'],
                    ['keys' => ['=='], 'description' => 'Auto-indent current line'],
                    ['keys' => ['J'], 'description' => 'Join line below to current line'],
                ],
            ],
            [
                'name' => 'Visual Mode',
                'commands' => [
                    ['keys' => ['v'], 'description' => 'Enter visual (character) mode'],
                    ['keys' => ['V'], 'description' => 'Enter visual line mode'],
                    ['keys' => ['Ctrl+v'], 'description' => 'Enter visual block mode'],
                    ['keys' => ['gv'], 'description' => 'Reselect last visual selection'],
                    ['keys' => ['o'], 'description' => 'Move to other end of selection'],
                    ['keys' => ['d'], 'description' => 'Delete (cut) selection'],
                    ['keys' => ['y'], 'description' => 'Yank (copy) selection'],
                    ['keys' => ['c'], 'description' => 'Change selection'],
                    ['keys' => ['>'], 'description' => 'Indent selection'],
                    ['keys' => ['<'], 'description' => 'Outdent selection'],
                    ['keys' => ['~'], 'description' => 'Toggle case of selection'],
                    ['keys' => ['u'], 'description' => 'Lowercase selection'],
                    ['keys' => ['U'], 'description' => 'Uppercase selection'],
                ],
            ],
            [
                'name' => 'Search & Replace',
                'commands' => [
                    ['keys' => ['/', '{pattern}'], 'description' => 'Search forward'],
                    ['keys' => ['?', '{pattern}'], 'description' => 'Search backward'],
                    ['keys' => ['n'], 'description' => 'Jump to next match'],
                    ['keys' => ['N'], 'description' => 'Jump to previous match'],
                    ['keys' => ['*'], 'description' => 'Search forward for word under cursor'],
                    ['keys' => ['#'], 'description' => 'Search backward for word under cursor'],
                    ['keys' => [':noh'], 'description' => 'Clear search highlights'],
                    ['keys' => [':%s/old/new/g'], 'description' => 'Replace all occurrences in file'],
                    ['keys' => [':%s/old/new/gc'], 'description' => 'Replace all with confirmation'],
                    ['keys' => [':s/old/new/g'], 'description' => 'Replace all on current line'],
                ],
            ],
            [
                'name' => 'Files & Buffers',
                'commands' => [
                    ['keys' => [':w'], 'description' => 'Save file'],
                    ['keys' => [':w', '{name}'], 'description' => 'Save to a new filename'],
                    ['keys' => [':q'], 'description' => 'Quit (fails with unsaved changes)'],
                    ['keys' => [':wq'], 'description' => 'Save and quit'],
                    ['keys' => [':x'], 'description' => 'Save (only if changed) and quit'],
                    ['keys' => [':q!'], 'description' => 'Force quit without saving'],
                    ['keys' => [':e', '{file}'], 'description' => 'Open / edit a file'],
                    ['keys' => [':bn'], 'description' => 'Switch to next buffer'],
                    ['keys' => [':bp'], 'description' => 'Switch to previous buffer'],
                    ['keys' => [':bd'], 'description' => 'Delete (close) current buffer'],
                    ['keys' => [':ls'], 'description' => 'List all open buffers'],
                ],
            ],
            [
                'name' => 'Windows & Tabs',
                'commands' => [
                    ['keys' => [':sp'], 'description' => 'Split window horizontally'],
                    ['keys' => [':vsp'], 'description' => 'Split window vertically'],
                    ['keys' => ['Ctrl+w', 'h'], 'description' => 'Move to left window'],
                    ['keys' => ['Ctrl+w', 'j'], 'description' => 'Move to window below'],
                    ['keys' => ['Ctrl+w', 'k'], 'description' => 'Move to window above'],
                    ['keys' => ['Ctrl+w', 'l'], 'description' => 'Move to right window'],
                    ['keys' => ['Ctrl+w', 'w'], 'description' => 'Cycle through windows'],
                    ['keys' => ['Ctrl+w', 'q'], 'description' => 'Close current window'],
                    ['keys' => ['Ctrl+w', '='], 'description' => 'Equalize all window sizes'],
                    ['keys' => [':tabnew'], 'description' => 'Open a new tab'],
                    ['keys' => ['gt'], 'description' => 'Go to next tab'],
                    ['keys' => ['gT'], 'description' => 'Go to previous tab'],
                    ['keys' => [':tabclose'], 'description' => 'Close current tab'],
                ],
            ],
            [
                'name' => 'Marks & Jumps',
                'commands' => [
                    ['keys' => ['m', '{a-z}'], 'description' => 'Set a local mark (a–z)'],
                    ['keys' => ['m', '{A-Z}'], 'description' => 'Set a global mark across files (A–Z)'],
                    ['keys' => ['`', '{mark}'], 'description' => 'Jump to exact position of mark'],
                    ['keys' => ["'", '{mark}'], 'description' => 'Jump to line of mark'],
                    ['keys' => ['Ctrl+o'], 'description' => 'Jump back in the jump list'],
                    ['keys' => ['Ctrl+i'], 'description' => 'Jump forward in the jump list'],
                    ['keys' => ["''"], 'description' => 'Jump to position before last jump'],
                    ['keys' => [':marks'], 'description' => 'List all marks'],
                ],
            ],
            [
                'name' => 'Macros',
                'commands' => [
                    ['keys' => ['q', '{a-z}'], 'description' => 'Start recording macro into register'],
                    ['keys' => ['q'], 'description' => 'Stop recording macro'],
                    ['keys' => ['@', '{a-z}'], 'description' => 'Play macro from register'],
                    ['keys' => ['@@'], 'description' => 'Replay last used macro'],
                    ['keys' => ['{n}', '@', '{a-z}'], 'description' => 'Run macro n times'],
                ],
            ],
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-950">

    {{-- Header --}}
    <div class="sticky top-0 z-10 border-b border-zinc-800/60 bg-zinc-950/90 backdrop-blur-sm">
        <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="flex items-baseline gap-2 text-lg font-semibold">
                        <span class="font-mono text-green-400">vim</span>
                        <span class="text-zinc-300">cheatsheet</span>
                    </h1>
                    <p class="mt-0.5 text-base text-zinc-600">{{ $this->totalMatching }} commands across {{ count($this->categories) }} categories</p>
                </div>

                <div class="w-full sm:w-72">
                    <flux:input
                        wire:model.live.debounce.150ms="search"
                        placeholder="Search commands..."
                        icon="magnifying-glass"
                        size="sm"
                        @keydown.escape="$wire.set('search', '')"
                    />
                </div>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

        @if (count($this->categories) === 0)
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <flux:icon.magnifying-glass class="mb-4 size-8 text-zinc-700" />
                <p class="text-sm text-zinc-400">
                    No commands match <span class="font-mono text-zinc-200">"{{ $search }}"</span>
                </p>
                <button
                    wire:click="$set('search', '')"
                    class="mt-3 text-base text-zinc-600 underline underline-offset-2 hover:text-zinc-400"
                >
                    Clear search
                </button>
            </div>
        @else
            <div class="space-y-10">
                @foreach ($this->categories as $category)
                    <section>
                        <h2 class="mb-3 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-zinc-600">
                            {{ $category['name'] }}
                            <span class="font-normal normal-case tracking-normal text-zinc-700">
                                {{ count($category['commands']) }}
                            </span>
                        </h2>

                        <div class="grid grid-cols-1 gap-px rounded-xl border border-zinc-800/60 bg-zinc-800/30 overflow-hidden sm:grid-cols-2">
                            @foreach ($category['commands'] as $command)
                                <div
                                    wire:key="{{ $category['name'] }}-{{ $loop->index }}"
                                    class="flex items-start gap-4 bg-zinc-950 px-4 py-3 hover:bg-zinc-900/70 transition-colors duration-75"
                                >
                                    {{-- Keys --}}
                                    <div class="flex min-w-0 shrink-0 flex-wrap items-center gap-1" style="min-width: 9rem">
                                        @foreach ($command['keys'] as $key)
                                            @if (str_starts_with($key, '{') && str_ends_with($key, '}'))
                                                <span class="font-mono text-base italic text-zinc-600">{{ $key }}</span>
                                            @else
                                                <kbd class="inline-flex items-center rounded border border-zinc-700 bg-zinc-800 px-1.5 py-0.5 font-mono text-base leading-none text-zinc-200 shadow-[0_1px_0_0_#27272a]">{{ $key }}</kbd>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Description --}}
                                    <span class="min-w-0 text-base leading-5 text-zinc-400">{{ $command['description'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            @if (trim($search) !== '')
                <p class="mt-8 text-center text-base text-zinc-700">
                    {{ $this->totalMatching }} {{ Str::plural('result', $this->totalMatching) }} for "{{ $search }}"
                </p>
            @endif
        @endif
    </div>
</div>
