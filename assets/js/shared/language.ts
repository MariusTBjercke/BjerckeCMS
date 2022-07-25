export class Language {
  public language: string;

  constructor(languageCode: string) {
    this.language = languageCode;
  }

  public getLanguage(): string {
    return this.language;
  }

  public setLanguage(language: string): void {
    this.language = language;
  }

  /**
   * Get a language string from the database via AJAX.
   *
   * @param alias Alias.
   */
  public async getLanguageStringByAlias(alias: string): Promise<string> {
    return await this.getLanguageString(alias);
  }

  /**
   * Get a language string by ID.
   *
   * @param id ID.
   */
  public async getLanguageStringById(id: number): Promise<string> {
    return await this.getLanguageString(id);
  }

  /**
   * Get a language string from the database via AJAX.
   *
   * @param variable ID or alias.
   */
  public async getLanguageString(variable: string|number) {
    const formData = new FormData();
    formData.append('action', 'get_language_string');
    formData.append('string', variable as string);

    return await fetch('/xmlhttprequest.php', {
      method: 'POST',
      body: formData
    }).then(response => response.text());
  }
}