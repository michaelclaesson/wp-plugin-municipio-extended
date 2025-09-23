import type { Alpine } from 'alpinejs';

export declare class ModuleFacade {
  constructor(modularity: any);
  isEditingModule(): HTMLElement | false;
  updateModule(moduleEl: HTMLElement, data: Record<string, any>): void;
  editingModule: HTMLElement | null;
}

declare global {
  interface ElementInternals {
    states: Pick<
      Set<string>,
      | 'size'
      | 'add'
      | 'clear'
      | 'delete'
      | 'entries'
      | 'forEach'
      | 'has'
      | 'keys'
      | 'values'
    >;
  }
  interface Window {
    Alpine: Alpine;
    Modularity: {
      Editor: {
        Module: ModuleFacade;
      };
    };
    mx: {
      moduleGroupsEnabled: boolean;
    };
  }
}

export {};
