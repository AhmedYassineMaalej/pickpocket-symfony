from typing import TYPE_CHECKING


if TYPE_CHECKING:
    from instructions import BrowserInstruction


from shared_types import WebDriver


class Browser:
    def __init__(self, driver: WebDriver):
        self.driver = driver

    def execute[T](self, instruction: "BrowserInstruction[T]") -> T:
        return instruction.accept(self)

    def quit(self):
        self.driver.quit()
